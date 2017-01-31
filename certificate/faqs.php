<?php
require_once('include/edgeward/global_functions.inc.php');

include_once('include/check_https.inc.php');

?>
<?php
//get new session id
if(isset($_SESSION['cert'])){
        session_regenerate_id(true);
}

//dont include JS
$no_js = 1;
$omniture_pageTitle = "Ancestry Shop Certificates";
?>
<?php
//if($layout['set'] >1){
if(1 == 1){
        require_once('include/edgeward/new_bmd/header.inc.php');
} else {
        require_once('include/edgeward/header.inc.php');
}

?>
<div id="content_page">
<h2>FAQs</h2>

<h3>What certificates can I order ?</h3>
<p>
We supply full (long form) official certified birth, marriage and death certificates registered in England and Wales.
</p>

<h3>What is a GRO index reference, and where can I find it?</h3>
<p>
The General Register Office allocates a reference to every event of birth, marriage or death registered in England and Wales. This relates to the year, quarter, and district that the entry was registered in. By giving the office this index reference it allows them to quickly identify the correct entry. This reference is only of use to the General Register Office and not to local registration offices who have their own system. 
</p>

<h3>What information will I see on a certificate?</h3>
<h4>The details contained on a full birth certificate include:</h4>
<ul>
	<li>Name, date and place of birth. </li>
	<li>Father's name (if given at time of registration), place of birth and occupation. </li>
	<li>Mother's name, place of birth, maiden surname and, after 1984, occupation. </li>
	<li>(Registrations made before 1969 do not include details of the parents' place of birth and mother's occupation.) </li>
</ul>
<h4>The details contained on a marriage certificate include:</h4>
<ul>
	<li>Date and place of marriage. </li>
	<li>Name, age and marital status of man and woman. </li>
	<li>Occupation and usual address. </li>
	<li>Name and occupation of each party's father. </li>
	<li>Names of the witnesses. </li>
	<li>Name of the person who solemnised the marriage. </li>
</ul>

<h4>The details contained on a death certificate include:</h4>
<ul>
	<li>Name, date and place of death. </li>
	<li>Date and place of birth (before 1969 a certificate only showed age of deceased). </li>
	<li>Occupation and usual address. </li>
	<li>Cause of death. </li>
	<li>The person who gave information for the death registration. </li>
</ul>

<h3>The certificate is not mine. Can I still order it?</h3>
<p>Under UK legislation, birth, marriage and death certificates are designated as 'public records', and as such anyone can request a duplicate certificate to be produced. The only caveat to this is that for births that occurred within the past 50 years, the full details are required to be provided (which includes full date of birth, and parents' names including the mother's maiden name). This is to protect against identity fraud.
</p>

<h3>How will I receive a refund if my application is unsuccessful?</h3>
<p>
If an application is unsuccessful, we will make a full refund directly to your credit / debit card account 
</p>

<h3>Can I cancel or amend my order once I've made it?</h3>
<p>
Due to the personalisation of this service we can not cancel or amend an order once it has been placed
</p>

<h3>When will my certificate(s) be despatched?</h3>
<p>Orders to the UK:</p>
<ul>
	<li><strong>Standard service:</strong> orders will be despatched up to the 16th working day after you place your order</li>
	<li><strong>Express service:</strong> orders will be despatched up to the 6th working day after you place your order</li>
</ul>
<p>Orders to non-UK countries:</p>
<ul>
	<li><strong>Standard service:</strong>  orders will be despatched up to the 28th working day after you place your order</li>
	<li><strong>Express service:</strong>  orders will be despatched up to the 16th working day after you place your order</li>
</ul>

<p>Applications made after 1pm Monday to Friday will be treated as the next day's business.  This includes the express service.  Applications may be made at the weekend or on a bank holiday but will not be processed until the next working day.
</p>


<?php 
if(isset($order['country'])):?>
	<h3>How much does the service cost?</h3>
<?php
	echo bmdFaqPrices($order['country'],$order['currency_symbol']);	
endif;
?>


<h3>Certificate - Digital copy</h3>
<p>
Our digital service provides a copy of your certificate via email to the email address you provide us with when you order your certificate, whilst you wait for the original to arrive in the post. Please note that we will always send the original as well as the email. The digital copy of the certificate will be emailed to you within 24 hours of the original certificate being posted.
</p>
<p>
Please note that the certificate will be ready according to delivery service speed you have chosen and cannot be emailed beforehand. This is up to <?php echo $standardDays;?> working days for Standard and up to <?php echo $expressDays;?> working days for Express.
</p>
<p>
A digital copy of the certificate will be securely retained for a period of three months from the date that it is emailed to you.  
</p>
After three months, the digital copy of the certificate will be permanently deleted and we will no longer be able to email you a copy.
<p>
We will provide an additional copy of the certificate within three months of emailing it to you only in the event that the original email is not received.  
</p>




</div>
<?php include ('include/footer.php');?>
