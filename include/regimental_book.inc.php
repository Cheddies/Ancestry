<?php include ('include/header.php');?>
	<?php include_once('include/page_header.inc.php');?>
	<?php include_once('include/nav.inc.php');?>
	<div id="content_wrapper" class="grid_12 alpha omega">
		<div id="page_content" class="grid_12">
			
			<h2 class="serif_title">Do you have an Ancestor who served in <br/><span class="serif_title_str">the Great War?</span></h2>
			<p class="side_intro"><strong>Great War Regimental Histories</strong> are a prime source for researching the stories of your Great War ancestors. They tell the story of an individual Soldiers Great War service in the context of the battles and campaigns in which his regiment fought. Most regiments produced such a history in the wake of the War, often written by a serving soldier with first hand knowledge of the men and actions he describes.</p> 
			
			<div style="border:1px dotted #999999; background: #c0c840 url(http://www.tracing-your-military-ancestry.com/skin1/images/search-back-strong.jpg) no-repeat right;  height:250px; font-family:Verdana,Arial,Helvetica,Sans-serif; width:100%;float:right;">
				<div style="padding:5px 0 0 25px; border:1px dotted #999999; width:500px; height:215px; margin:15px; background: #F8F0E1 url(http://www.tracing-your-military-ancestry.com/skin1/images/search-back.jpg);">
					<h1 style="font-size: 22px; color:222222; font-weight:normal; letter-spacing:-1px; text-align:left; padding-top:10px;padding-bottom:0px; margin-bottom:5px;">Search for your ancestors Regimental History</h1>
					<p>Enter your ancestors regiment or Battalion below.<br />We will then show you which books are available that might aid your research.</p>
					<form method="get" action="http://www.tracing-your-military-ancestry.com/_search.php" target="_blank" name="_search"><input type="hidden" name="page" value="1" />
						<div style="background-color:#FFFFFF;border:1px dotted #999999;padding:20px;width:360px;margin:0 0 15px 0;overflow:hidden;">
							<div style="float:left;">
								<label for="mreg_search" style="padding-left: 20px; padding-right: 5px;color: #414141;font-size: 14px;font-weight: bold;">Search:</label>
								<input id="mreg_search" name="q" type="text" value="" size="30" />
							</div>
							<a href="javascript: document._search.submit();" style="display:inline;float:left;margin:0 0 0 10px;">
								<img src="http://www.ancestryshop.co.uk/images/layout/btn-search.png" class="GoImage" alt="" border="0" />
							</a>
						</div>
					</form>
					<p>or <a href="http://www.tracing-your-military-ancestry.com/home.php?cat=310" target="_blank" style=" color:#534e45;">browse our Great War books</a></p>
				</div>
			</div>
			

		</div>
		<?php include_once('include/page_footer.inc.php');?>		
	</div><!--end #content_wrapper-->