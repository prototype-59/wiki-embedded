<?php 
/**
 * wiki-embedded 

 * @author Aleksandar Radovanovic <aleksandar@radovanovic.com>
 * @version 2016-05-03
*/
?>
<script>
$(function() {

/*  wiki settings
============================================================================ */

	var wikiurl = "wiki/"; 	// <- set the relative path to wiki
	var pagename = "main.md";	// <- set start page

/*  dont change bellow this point
===============================================================================
*/
	$( "#wiki" ).load(wikiurl+"page.php?&p=" + pagename);
	
	// make pages editable id data-editable is set to true
	if ($("#wiki").data("editable")===true) {
		$("#edit-section").show();
	}

	// attach click on links inside wikipage
	$("#wiki").on("click", "a", function(event) {
		event.preventDefault();	
		$( "#wiki" ).empty();
		pagename = $(this).attr("href");
		if (pagename.indexOf("http://") >= 0 || pagename.indexOf("https://") >= 0) {
			window.location.href = pagename;
		} else {
			pagename += ".md";
			$( "#wiki" ).load(wikiurl+"page.php?p=" + pagename);
		}
	});

	// create a new page
	$("#create-page").on("click", function(e){
		e.preventDefault();
		$("#wiki-editform").hide();
		$("#wiki-createform").show();
		return false;
	});
	$( "#wiki-createform" ).submit(function( event ) {
		event.preventDefault();
		var regex = new RegExp("^[a-zA-Z0-9]+$");
		if (!regex.test($("#newpage-name").val())) {
			alert("Please use alphanumeric names only!");
		}
		pagename = $("#newpage-name").val()+".md";
		var content = "* [Wiki home](main)\n\n---\n##" + $("#newpage-name").val();
		$.get(wikiurl+"update.php",{name:pagename,content:content}, function(){
			$( "#wiki" ).empty();
			$( "#wiki" ).load(wikiurl+"page.php?p=" + pagename);
			$('#wiki-createform').hide();
			$('#wiki-createform')[0].reset();
		});
		return false;
	});

	// edit existing
	$("#edit-page").on("click", function(e){
		e.preventDefault();
		$("#wiki-createform").hide();
		$.get({url:wikiurl+"pages/"+pagename, cache: false}, function( page ) {
			$("#wikipage-text").val(page);
			$("#wiki-editform").show();
		});
		return false;
	});
	$( "#wiki-editform" ).submit(function( e ) {
		e.preventDefault();
		var content = $("#wikipage-text").val();
		$.get(wikiurl+"update.php",{name:pagename,content:content}, function(){
			$( "#wiki" ).empty();
			$( "#wiki" ).load(wikiurl+"page.php?p=" + pagename);
			$('#wiki-editform').hide();
			$('#wiki-editform')[0].reset();
		});		
		return false;
	});
	// remove this page
	$("#remove-page").on("click", function(e){
		e.preventDefault();
		$.get(wikiurl+"remove.php",{name:pagename}, function(){
			$( "#wiki" ).empty();
			pagename = "main.md";
			$( "#wiki" ).load(wikiurl+"page.php?p=" + pagename);
		});
		return false;
	});

	// list all pages
	$("#list-pages").on("click", function(e){
		e.preventDefault();
		$.get(wikiurl+"list.php", function(){
			$( "#wiki" ).empty();
			pagename = "list.md";
			$( "#wiki" ).load(wikiurl+"page.php?p=" + pagename);
		});
		return false;
	});

});
</script>

<div id="edit-section" style="display:none;border-bottom:1px solid black;margin-bottom:10px;">
	<label id="edit-page">Edit page</label>&nbsp;|&nbsp;
	<label id="create-page">Create a new page</label>&nbsp;|&nbsp;
	<label id="list-pages">List all pages</label>&nbsp;|&nbsp;
	<label id="remove-page">Remove this page</label>
</div>

<div id="edit-section" style="display:none;border-bottom:1px solid black;margin-bottom:10px;">
	<label id="edit-page">Edit page</label>&nbsp;|&nbsp;
	<label id="create-page">Create a new page</label>
</div>

<form name="wiki-createform" id="wiki-createform" method="get" style="display:none;border-bottom:1px solid black;padding-bottom:10px;">
	<input type="text" name="newpage-name" id="newpage-name" value="" placeholder="new page name">
	<input type="submit"  value="Submit" />
	<input type="reset" value="Cancel" onclick="$('#wiki-createform').hide();"/>
</form>

<form name="wiki-editform" id="wiki-editform" method="get" style="display:none;border-bottom:1px solid black;padding-bottom:10px;">
	<textarea id="wikipage-text" rows="25" style="width:100%"></textarea>
	<input type="submit" value="Submit" />
	<input type="reset" value="Cancel" onclick="$('#wiki-editform').hide();"/>
</form>
