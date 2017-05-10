<?php 
/**
 * wiki-embedded 

 * @author Aleksandar Radovanovic <aleksandar@radovanovic.com>
 * @version 2017-05-08
*/
if (session_status() == PHP_SESSION_NONE) { session_start(); }
$_SESSION['wiki-editable'] = $wikisettings["editable"];
?>
<script>
$(function() {
	// set the relative path to wiki, database name(subdirectory) and default page name
	var wikiroot = "<?php print $wikisettings["path"]?>";
	var wikiurl = wikiroot + "page.php"; 
	var wdb	= "<?php print $wikisettings["wdb"] . "/" ?>";
	var pagename = "main";	// <- set start page
	$( "#wiki" ).load(wikiurl, {a:"s",db:wdb,p:pagename},function(){ $( "#wiki" ).toc(); });
	var regex = new RegExp();
	
	// show edit menu if data-editable is set to 1
	if ($("#wiki").data("editable") == 1) {
		$("#wiki-edit-menu").show();
	}

	// attach click on links inside wikipage
	$("#wiki").on("click", "a", function(event) {
		event.preventDefault();	
		var link = $(this).attr("href");
		regex = RegExp(/http(s?):/i);
		if (regex.test(link))  {
			$( "#wiki" ).empty();
			window.location.href = link;
			return;
		} 
		// anchor link
		if( link.charAt(0) === "#" ) {
			//location.href = link; // no smooth scrolling
			$('html,body').animate({scrollTop:$(link).offset().top}, 500); // smooth scrolling
			return;
		}
		// link to files
		regex = RegExp(/\.(avi|doc|docx|jpg|mkv|mov|mp3|mp4|pdf|png|ppt|pptx|rar|rtf|svg|tex|txt|zip)$/i);
		if (regex.test(link))  {
			window.location.href = wikiroot+"uploads/"+link;
			return;
		} 
		// another wiki page link
		pagename = link;
		$( "#wiki" ).empty();
		$( "#wiki" ).load(wikiurl, {a:"s",db:wdb,p:pagename},function(){ $( "#wiki" ).toc(); });
	});

	// create a new page
	$("#create-page").on("click", function(e){
		e.preventDefault();
		$( "form" ).hide(); $("#wiki-searchform").show();
		$("#wiki-createform").show();
		return false;
	});
	$( "#wiki-createform" ).submit(function( event ) {
		event.preventDefault();
		regex = RegExp("^[a-zA-Z0-9\-\_\]+$");
		if (!regex.test($("#newpage-name").val())) {
			alert("Please use alphanumeric names including '-' & '_' only!");
			return false;
		}
		pagename = $("#newpage-name").val();
		$.post(wikiurl,{a:"c",db:wdb, p:pagename}, function(){
			$( "#wiki" ).empty();
			$( "#wiki" ).load(wikiurl, {a:"s",db:wdb,p:pagename},function(){ $( "#wiki" ).toc(); });
			$( "form" ).hide(); $("#wiki-searchform").show();
			$('#wiki-createform')[0].reset();
		});
		return false;
	});

	// search
	$( "#wiki-searchform" ).submit(function( event ) {
		event.preventDefault();
		var searchfor = $("#wiki-searchfor").val();
		if ( searchfor.length <= 2) {
			alert("type at least 3 letter word!");
			return false;
		}
		$.post(wikiurl,{a:"f",db:wdb, search:searchfor}, function(page){
			$( "#wiki" ).empty();
			$( "#wiki" ).html(page);
		});
		$( "form" ).hide(); $("#wiki-searchform").show();
		return false;
	});

	// edit existing
	$("#edit-page").on("click", function(e){
		e.preventDefault();
		$( "form" ).hide(); $("#wiki-searchform").show();
		$.post({url:wikiurl, cache:false, data:{a:"o",db:wdb,p:pagename}}, function(page){
			if (page != "error") {
				$("#wikipage-text").val(page);
				$("#wiki-editform").show();
			}
		});
		return false;
	});
	$( "#wiki-editform" ).submit(function( e ) {
		e.preventDefault();
		var content = $("#wikipage-text").val();
		$.post(wikiurl,{a:"u",db:wdb,p:pagename,content:content}, function(){
			$( "#wiki" ).empty();
			$( "#wiki" ).load(wikiurl, {a:"s",db:wdb,p:pagename},function(){ $( "#wiki" ).toc(); });
			$('#wiki-editform')[0].reset();
			$( "form" ).hide(); $("#wiki-searchform").show();
		});		
		return false;
	});
	// remove this page
	$("#remove-page").on("click", function(e){
		e.preventDefault();
		$( "form" ).hide(); $("#wiki-searchform").show();
		$.post(wikiurl,{a:"r",db:wdb,p:pagename}, function(){
			$( "#wiki" ).empty();
			pagename = "main";
			$( "#wiki" ).load(wikiurl, {a:"s",db:wdb,p:pagename},function(){ $( "#wiki" ).toc(); });
		});
		return false;
	});

	// list all pages
	$("#list-pages").on("click", function(e){
		e.preventDefault();
		$.post(wikiurl, {a:"l",db:wdb}, function(list){
			$( "#wiki" ).empty();
			pagename = "nopage.txt"; // non-existing page in case user clicks on "remove this page"
			$( "#wiki" ).html(list);
		});
		$("form").hide;$("#wiki-searchform").show();
		return false;
	});

	// File upload
	$("#file-upload").on("click", function(e){
		e.preventDefault();
		$('#fileinfo').show();
		return false;
	});
	$( "#fileinfo" ).submit(function( e ) {
		e.preventDefault();
		var regex = new RegExp("^[a-zA-Z0-9\-\.\_\]+$");
		// unix/windows style various browsers path process
		var filename= $("#file").val().split('/').pop(); filename= filename.split('\\').pop();
		if (!regex.test(filename)) {
			alert(filename+": Please use alphanumeric names including '-' & '_' only!");
			return false;
		}
		var fd = new FormData($("#fileinfo")[0]);
        fd.append("a", "t"); // action: (t)ransfer file
        $.ajax({
            url: wikiurl,  
            type: 'POST',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function(){
  			//console.log("Success: Files sent!");
		}).fail(function(){
  			alert("An error occurred, the files couldn't be sent!");
		});
		$( "form" ).hide(); $("#wiki-searchform").show();
		return false;
	});
});
// generate the table of content
(function( $ ) {
	$.fn.toc = function() {   
	if( !$("#toc").length )  return; // no toc request   
	$("#toc").empty();
	$( '<ol id="tocList"></ol>' ).appendTo( "#toc" );                                                  
	var H2Item = null;                                                             
	var H2List = null;                                                             
    
	var index = 0;                                                                     
	$("h2, h3").each(function() {                                                      
		var anchor = "<div id='" + index + "'></div>";  
		$(this).before(anchor);                                                        
		var li = '<li><a href="#' + index + '">' +  $(this).text() + '</a></li>';
    	if( $(this).is("h2") ){                                                        
			H2List = $("<ol></ol>");                                               
			H2Item = $(li);                                                        
			H2Item.append(H2List);                                             
			H2Item.appendTo(tocList);                                              
		} else {                                                                       
			H2List.append(li);                                                     
		}                                                                              
		index++;                                                                       
	});
	$( "<h2>Contents</h2>" ).prependTo( $( "#toc" ) );
} 
}( jQuery ));
</script>
<div id="wiki-edit-menu" style="display:none;">
	<a href="#" id="edit-page">Edit page</a>&nbsp;
	<a href="#"  id="create-page">Create a new page</a>&nbsp;
	<a href="#"  id="remove-page">Remove this page</a>
	<a href="#"  id="file-upload">File upload</a>
	<a href="#"  id="list-pages">List pages & files</a>&nbsp;
	
</div>

<div id="wiki-forms">
<form name="wiki-createform" id="wiki-createform" method="get" style="display:none;">
	<input type="text" name="newpage-name" id="newpage-name" value="" placeholder="new page name">
	<input type="submit"  value="Submit" />
	<input type="reset" value="Cancel" onclick="$('#wiki-createform').hide();"/>
</form>

<form name="wiki-editform" id="wiki-editform" method="get" style="display:none;">
	<textarea id="wikipage-text" rows="25" style="width:100%"></textarea>
	<input type="submit" value="Submit" />
	<input type="reset" value="Cancel" onclick="$('#wiki-editform').hide();"/>
</form>
</div>

<form name="fileinfo" id="fileinfo" method="post" enctype="multipart/form-data" style="display:none;">
    <input type="file" name="file" id="file" required>
    <input type="submit" value="File upload">
</form>

<form name="wiki-searchform" id="wiki-searchform">
	<input type="text" id="wiki-searchfor">
	<input type="submit" value="Search" />
</form>

<div id="wiki" data-editable="<?php print $wikisettings["editable"]; ?>"></div>
