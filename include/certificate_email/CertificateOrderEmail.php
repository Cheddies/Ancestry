<?php
require_once ("classes/mysql_class.php");


class CertificateOrderEmail {
    
    var $orderRef;    
    var $niceOrderRef;
    var $certDetails;
    var $billingAddress;
    var $deliveryAddress;
    var $orderDetails;
    
    var $certificate;
    
    var $certTypes = array('1'=>'Birth Certificate', '2'=>'Marriage Certificate', '3'=>'Death Certificate');
    
    var $regionInfo = array(
        'Australia' =>array(
            'currency_prefix_text'=>"$",
            'currency_prefix_html'=>"$",
            'currency_suffix'=>" AUD",
            'show_VAT'=>false
        ),
        'United States'=>array(
            'currency_prefix_text'=>"$",
            'currency_prefix_html'=>"$",
            'currency_suffix'=>" US",
            'show_VAT'=>false
        ),
        'Canada'=>array(
            'currency_prefix_text'=>"$",
            'currency_prefix_html'=>"$",
            'currency_suffix'=>" CAD",
            'show_VAT'=>false
        ),
        'United Kingdom'=>array(
            'currency_prefix_text'=>"£",
            'currency_prefix_html'=>"&pound;",
            'currency_suffix'=>"",
            'show_VAT'=>true
        )
    );
    
    function __construct($orderRef, $niceOrderRef) {
        $this->orderRef = $orderRef;
        $this->niceOrderRef = $niceOrderRef;
        $this->_getOrderRecord();
    }
    
    function test($emailAddress = ''){
        include 'template/Confirmation_Email.php';
        echo $htmlEmail;
        echo '<textarea style="width:800px; height:800px;">' . $textEmail . '</textarea>';
        if($emailAddress){
            $this->sendEmail($emailAddress);
        }
    }
    
    function currency($amount, $symbol = ''){
        if(!$symbol) $symbol = $this->regionInfo[$this->billingAddress['country']]['currency_prefix_html'];
        return $symbol . sprintf('%.2f', $amount);
    }
    
    function htmlOrderInfo(){
        $res = '';
        foreach($this->certDetails as $k => $v){
            if(!strlen(trim($v))) continue;
            $res .= '<tr>';
            $res .= '<td valign="top" style="line-height:160%;"><strong>' . $k . ':</strong></td>';
            $res .= '<td valign="top" style="line-height:160%;">' . $v . '</td>';
            $res .= '</tr>';
        }
        return $res;
    }
    
    private function _getOrderRecord(){
        $DB = new MySQL_DB(DB_HOST, DB_NAME, DB_USER, DB_PASS);
        $ordernumber = $this->orderRef;
        
        $query = "SELECT GRO_orders_id , order_number , billing_address , shipping_address , order_date , GRO_tbl_shipping.description , GRO_tbl_shipping.price , email , phone , order_status , total_paid , discount_code , discount FROM GRO_orders LEFT JOIN GRO_tbl_shipping on delivery_method = GRO_tbl_shipping.code WHERE order_number='{$this->orderRef}'";
        $orderHeaders = $DB->getFirst($query);        
        
        $query = "SELECT certificate_id , certificate_type FROM GRO_certificate_ordered WHERE order_number='{$this->orderRef}'";
        $certDetails = $DB->getFirst($query);
        
        $query = "SELECT first_name , surname , line_1 , line_2 , city , county , postcode , GRO_tbl_countries.country , title FROM GRO_addresses LEFT JOIN GRO_tbl_countries on code=GRO_addresses.country WHERE GRO_addresses_id='{$orderHeaders['billing_address']}'";
        $this->billingAddress = $DB->getFirst($query);
        
        $query = "SELECT first_name , surname , line_1 , line_2 , city , county , postcode , GRO_tbl_countries.country , title FROM GRO_addresses LEFT JOIN GRO_tbl_countries on code=GRO_addresses.country WHERE GRO_addresses_id='{$orderHeaders['shipping_address']}'";
        $this->deliveryAddress = $DB->getFirst($query);

        $GROFields = array("GRO_index_year AS 'Year'", "GRO_index_quarter AS 'Quarter'", "GRO_index_district AS 'District Name'", "GRO_district_no AS 'District Number'", "GRO_reg_no AS 'Reg Number'", "GRO_entry_no AS 'Entry Number'", "GRO_index_volume AS 'Volume Number'", "GRO_index_page AS 'Page Number'", "GRO_index_reg AS 'Reg'");
        
        switch ($certDetails['certificate_type']) {
            CASE BIRTH:
                $requiredFields = array("birth_reg_year AS 'Year Birth Was Registerd'", "birth_surname AS 'Surname at Birth'", "forenames AS 'Forenames'", "DOB", "birth_place AS 'Place of Birth'", "fathers_surname AS 'Father''s Surname'", "fathers_forenames AS 'Father''s Forename'", "mothers_maiden_surname AS 'Mother''s Maiden Surname'", "mothers_surname_birth AS 'Mother''s Surname at Time of Birth'", "mothers_forenames AS 'Mother''s Forename(s)'", "copies", "scan_and_send");
                $table = 'GRO_birth_certificates';
                break;
            CASE DEATH:
                $requiredFields = array("registered_year AS 'Year Death was Registered'", "surname_deceased AS 'Surname of Deceased'", "forenames_deceased AS 'Forename(s) of Deceased'", "death_date AS 'Date of Death'", "relationship_to_deceased AS 'Relationship to the Deceased'", "death_age AS 'Age at Death in Years'", "fathers_surname AS 'Father''s Surname'", "fathers_forenames AS 'Father''s Forename(s)'", "mothers_surname AS 'Mother''s Surname'", "mothers_forenames AS 'Mother''s Forename(s)'", "copies", "scan_and_send");
                $table = 'GRO_death_certificates';
                break;
            CASE MARRIAGE:
                $requiredFields = array("registered_year AS 'Year Marriage was Registered'", "mans_surname AS 'Man''s Surname'", "mans_forenames AS 'Man''s Forenames'", "womans_surname AS 'Woman''s Surname'", "womans_forenames AS 'Woman''s Forenames'", "copies", "scan_and_send");
                $table = 'GRO_marriage_certificates';
                break;                
        }
        $query = "SELECT ";
        $query .= implode(',', $requiredFields) . ',' . implode(',', $GROFields);
        $query .= " FROM {$table} WHERE {$table}_id='{$certDetails['certificate_id']}'";
        $groDetails = $DB->getFirst($query);  
        //move scan and send and copies to details
        $orderHeaders['copies'] = $groDetails['copies'];
        $orderHeaders['scan_and_send'] = $groDetails['scan_and_send'] ? 'Yes' : 'No';
        unset($groDetails['scan_and_send'],$groDetails['copies']);
        $this->certDetails = $groDetails;
        $this->orderDetails = $orderHeaders;
        $this->certificate = $this->certTypes[$certDetails['certificate_type']];        
    }
    
    function sendEmail($emailAddress = '') {
        include 'template/Confirmation_Email.php';
        $plainText =  '<textarea style="width:800px; height:800px;">' . $textEmail . '</textarea>';
        $notice_text = "This is a multi-part message in MIME format.";
        $plain_text = $mailbody_text;
        $html_text = $mailbody_html;

        $semi_rand = md5(time());
        $mime_boundary = "==MULTIPART_BOUNDARY_$semi_rand";
        $mime_boundary_header = chr(34) . $mime_boundary . chr(34);

        $to = $emailAddress ? $emailAddress : $this->orderData['email'];
        $from = "service@ancestryshop.co.uk";
        $subject = "The Ancestry Shop Order Confirmation";

        $body = "{$notice_text}

--{$mime_boundary}
Content-Type: text/plain; charset=us-ascii
Content-Transfer-Encoding: 7bit

{$plainText}

--{$mime_boundary}
Content-Type: text/html; charset=us-ascii
Content-Transfer-Encoding: 7bit

{$htmlEmail}

--{$mime_boundary}--";

        mail($to, $subject, $body,"From: " . $from . "\n" ."MIME-Version: 1.0\n" ."Content-Type: multipart/alternative;\n" ."     boundary=" . $mime_boundary_header);
    }
    
    
    /**
     * Returns an hashed array of discount information to include in the response email
     * array('code','discount percent')
     * @param object $orderNo
     * @return array
     */
    private function _emailPromoCode($orderNo){
        $codes = array(
            'BMD10' => array('code'=>'BMD10','discount'=>10,'next'=>'')
        );
        $discInfo = $codes['BMD10'];

        return $discInfo;
    }
    
    function addBusinessDays($startdate, $buisnessdays, $holidays, $dateformat){
        $i=1;
        $dayx = strtotime($startdate);
        while($i < $buisnessdays){
            $day = date('N', $dayx);
            $date = date('Y-m-d', $dayx);
            if($day < 6 &&!in_array($date, $holidays))$i++;
            $dayx = strtotime($date.' +1 day');
        }
        return date($dateformat, $dayx);
    }
    
}

<?php /*
Omniture tracking
*/ ?>

<script type='text/javascript' src='http://c.mfcreative.com/js/omniture004.js?v=5'></script>
<script type='text/javascript'>
s.products='birth-marriage-death-certificates;birth-certificate;1;22.99,birth-marriage-death-certificates;marriage-certificate;1;22.99,birth-marriage-death-certificates;death-certificate;1;22.99';
//Product Category ; Product Name ; Quantity ; Total Price (all lower case)Price (all lower case)
	s.events='purchase';
	s.pageName=document.title;
	var s_code=s.t();if(s_code)document.write(s_code);
</script>