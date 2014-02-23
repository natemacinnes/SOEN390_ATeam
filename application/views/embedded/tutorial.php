
<script>
var enURL = "<?php echo $enurl; ?>";
var frURL = "<?php echo $frurl; ?>";

function makeFrame(url){
	ifrm = document.createElement("IFRAME");
	ifrm.setAttribute("src", url);
	ifrm.style.width = 800+"px"; 
    ifrm.style.height = 600+"px"; 
    return ifrm
}

function onSwitch(lang){

if(lang==0){
	document.getElementById('videowrapper').innerHTML="";
	document.getElementById('videowrapper').appendChild(makeFrame(enURL));
	
	console.log("0");
}
else if(lang==1){
	document.getElementById('videowrapper').innerHTML="";
	document.getElementById('videowrapper').appendChild(makeFrame(frURL));
	console.log("1");
}


}
</script>


<div class="filter-container">
        <div class="btn-group btn-group-horizontal">
          <a href="#english" onclick="onSwitch(0)" title="English&#013;Anglais" class="btn btn-default colorbox" role="button"><span class="flagicon flagicon-en"></span></a>
          <a  onclick="onSwitch(1)" title="French&#013;Fran&ccedil;ais" class="btn btn-default colorbox" role="button"><span class="flagicon flagicon-fr"></span></a>
        </div>
</div>
<div id="videowrapper">
<iframe id="video" width="800" height="600" src="<?php echo $enurl; ?>" frameborder="0" allowfullscreen></iframe>
</div>
