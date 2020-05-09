<h2>HTML5 File Upload Progress Bar Tutorial</h2>
<form id="upload_form" enctype="multipart/form-data" method="post">
	<input type="file" name="file1" id="file1"><br>
	<input type="button" value="Upload File" onclick="uploadFile()">
	<div class="progress ml-5 mt-5" style="width: 50%;">
  		<div id="progressBar" class="progress-bar text-center" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
	</div>
	<small id="loaded_n_total" class="ml-5">Uploaded 0MB bites of 0MB</small>
</form>
<style type="text/css">
	*{
		/*border: 1px solid red;*/
	}
</style>

<script type="text/javascript">
	function _(el){
		return document.getElementById(el);
	}

	function uploadFile(){
		var file = _("file1").files[0];
		// alert(file.name+"|"+file.size+"|"+file.type);
		var formdata = new FormData();
		formdata.append("file", file);
		var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", progressHandler, false);
		ajax.addEventListener("load", completeHandler, false);
		ajax.addEventListener("error", errorHandler, false);
		ajax.addEventListener("abort", abortHandler, false);
		ajax.open('post', 'http://localhost/uploadfile/welcome/upload_progress');
		ajax.send(formdata);
	}

	function progressHandler(e){
		// console.log(e);
		_("loaded_n_total").innerHTML = "Uploaded "+(e.loaded/1000000).toFixed(2)+"MB bites of "+(e.total/1000000).toFixed(2)+"MB";
		var percent = (e.loaded / e.total)*100;
		_("progressBar").innerHTML = Math.round(percent)+"%";
		_("progressBar").style.width = percent+'%';
		_("progressBar").setAttribute('aria-valuenow', percent);
	}

	function completeHandler(e){
		_("progressBar").innerHTML = e.target.responseText;
	}

	function errorHandler(e){
		_("progressBar").innerHTML = "Upload Failed";
	}

	function abortHandler(e){
		_("progressBar").innerHTML = "Upload aborted"
	}
</script>