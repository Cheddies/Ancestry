<?php
$header="";
$text="";

if(isset($clean['dept']))
{
	
		
	switch ($clean['dept'])
	{
	case 1004:
		$header="The Heritage Range - Men's";
		$text="Inspired by the incredible history of the RAF this range brings durability and quality to the fore. Designed for the more discerning man, the Heritage Range provides everything necessary across weekend and outdoor styles from the leather flying jacket, fleeces, rugby shirts, chinos, polo shirts and shorts which provide the under-stated style and sophistication so redolent of the Royal Air Force.";
	break;
	case 1005:
		$header="The Classic Polo Shirts";
		$text="Even Tiger Woods can't drive as fast as Land Speed World Record holder Wg Cmdr Andy Green, who proudly endorses this brand.  And whether you're driving down Oxford St or off the first at St Andrew's, these classic styles bring with them the effortless refinement of the RAF. Focusing on the brevet, synonymous around the world with the Royal Air Force, this range lets you share in the pride which this nation holds in its air force.";
	break;
	case 1006:
		$header="The Off Duty Range - Men's";
		$text="Proud to represent this world-beating military force both on and off duty, the men and women of the Royal Air Force need tough, hard-wearing yet stylish clothing in everything they do. And the Off Duty Range lets you be part of this. With a strong emphasis on the visual styling and design work, this British gear is perfect for the young - and the young at heart.";
	break;
	case 1007:
		$header="The Off Duty Range - Women's";
		$text="Building on the hard-wearing yet stylish theme of the men's Off Duty clothing, the Ladies' range brings an added level of functionality through the classic swimwear lines. Designed for ladies who want to look good when they're off duty, the strong design styles across the t-shirts, the jeans and the linen trousers also bring the quality of the Royal Air Force with the assuredness of great style.";
	break;
	case 1008:
		$header="Rugby/Sweatshirts";
		$text="The RAF only accepts candidates of the highest quality and sports are a natural part of their daily routine. Much like the people who inspired them, these garments are built to last and created using the finest materials. This stylish casualwear is perfect for an active lifestyle or as more sedentary pub-wear.";
	break;
	case 1009:
		$header="The Classic Polo Shirts";
		$text="Even Tiger Woods can't drive as fast as Land Speed World Record holder Wg Cmdr Andy Green, who proucly endorses this brand.  And whether you're driving down Oxford St or off the first at St Andrew's, these classic styles bring with them the effortless refinement of the RAF. Focusing on the brevet, synonymous around the world with the Royal Air Force, this range lets you share in the pride which this nation holds in its air force.";
	break;
	case 1010:
		$header="T-shirts";
		$text="The modern RAF comprises some of the most versatile aircraft in the world and these cutting-edge designs reflect the force's role as a forward-thinking organisation. Perfect streetwear, these t-shirts are sure-fire winners in the fashion market";
	break;
	case 1011:
		$header="Trousers/shorts";
		$text="Former England Rugby players, the Underwoods, take up position on the wing here with the fastest man in the world Wg Cmdr. Andy Green to show off the RAF Collection's trouser range. The high quality denim jeans demonstrate the durability expected of this world-beating military force, whilst the smart-casual chinos reflect the debonair quality synonymous with the Royal Air Force.";
	break;
	case 1012:
		$header="Flying Jackets";
		$text="In the freezing conditions experienced by many of the airmen in the Second World War, high quality apparel meant the difference between an adaptable, dynamic fighting aircraft and death. Inspired by the designs worn in this period, these quality flying jackets bring this necessarily high level of performance to the high street.";
	break;
	case 1013:
		$header="Formal Shirts";
		$text="Rise above the rest in your place of work with this classic range sporting the instantly recognisable livery of the RAF. With incredible attention to detail, these garments include the embroidered phrase 'Second to None' in the back neck and enamelled roundel studs so you can take pride in everything you do.";
	break;
	case 1017:
		$header="All men's garments";
		$text="Across the whole of the RAF Collection, leading designers have taken inspiration from the incredible heritage of the RAF as well as its role as a force for good in the modern world, to create a range of fashion forward garments. From the relaxed to the formal, this collection allows men to feel what it is to be part of this world-leading military force. ";
	break;
	case 1014:
		$header="Tops";
		$text="Something for everyone, these garments sport some of the classic icons of the RAF in a highly fashionable range. From the semi-fitted polo shirts to the stylish sweatshirts, these tops will look great wherever you are.";
	break;
	case 1015:
		$header="Trousers";
		$text="With the subtly embroidered icons of the RAF, this range is ideal for summer wear with the linen trousers or for harsher seasons with the fashion-conscious denim jeans. ";
	break;
	case 1016:
		$header="Swimwear";
		$text="If you're going to join the Caterpillar Club, you need the right equipment in case you come down in the drink! This stylish range of ladies' swimwear takes the world-famous fashion icon the roundel and transforms it for use on the beach.";
	break;
	case 1018:
		$header="Off Duty Children's Range";
		$text="Red Arrows shopping at RAF collection marquee";
	break;
	case 1019:
		$header="Children's Heritage Collection ";
		$text="";
	break;
	case 1021:
		$header="Airshow Merchandise";
		$text="For the list of airshows the rafcollection will be attending please see the page ‘FAQ’";
		break;
	default:
		$header="The RAF Collection";
		$text="";
	break;
	}
	
}
else
{
	if(isset($Page))
	{
		switch ($Page)
		{
			case "index":
				$header="Welcome to the RAF Collection";
				$text=
					"The RAF takes on a different type of runway swapping tarmac for the bright lights of the fashion world with the launch of their new casual clothing collection.

					From 'their finest hour' to the cutting edge technology of the modern air force, no other brand in the UK carries with it the inherent Britishness and quality of the Royal Air Force and now you can wear their badges with pride. Developed by a collection of leading designers working with the RAF, this collection builds on the key requirements for air force personnel and equipment: performance under pressure, dependability and capability.

					With durability in mind, these highly fashionable garments have been double-stitched and are produced using high quality materials and finishes across all three ranges. The Heritage Range brings influences from the RAF's proud history into the modern day with a product line that includes leather flying jackets, fleeces and rugby shirts. The Classic Polo Shirts Range provides an opportunity for a fashionable smart casual look with eight colours to choose from. The Off Duty Range brings forward the more modern face of the air force with a rugged, sporting approach for both men and ladies; and the Kids' Range brings a new dimension of performance to children's casual clothing.

					Clothing is available across a full range of sizes which makes the RAF Collection ideal for all, whether you're buying for yourself or as a gift.

					Our high level of service is assured by our no-quibble money back guarantee and our promise to despatch all garments within five working days of receipt of order.
					";
			break;
			case "basket":
				$header="Thank you for shopping";
				$text="";
			break;
			case "checkout":
				$header="Please complete your details";
				$text="";
			break;
			case "checkout1a":
				$header="Please complete your details";
				$text="";
			break;
			case "checkout1b":
				$header="Please complete your details";
				$text="";
			break;
			case "checkout2":
				$header="Please complete your details";
				$text="";
			break;
		}
	}
}
?>
<div id="main_image"> 
	<div id="main_text">
	<h3><?php echo $header;?></h3>
	<p>
	<?php echo(formatdescription($text));?>
	</p>
	</div>
</div>