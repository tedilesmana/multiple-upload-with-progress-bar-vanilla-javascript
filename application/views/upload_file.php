<style type="text/css">
	* {
		margin: 0px;
		padding: 0px;
		/*border: 1px solid red;*/
	}

	body {
		font-family: "Arial", sans-serif;
	}

	.dropzone {
		width: 100%;
		height: 300px;
		border: 2px dashed #ccc;
		color: #ccc;
		line-height: 300px;
		text-align: center;
	}

	.dropzone.dragover {
		border-color: green;
		color: green;
	}
</style>

<div id="uploads"></div>
<div class="dropzone" id="dropzone">Drop files here to upload</div>
<div id="listProgress" style="width: 70%;"></div>

<script type="text/javascript">

	function hapusFile(token){
		console.log(token);
	}

	(function(){
		var dropzone = document.getElementById('dropzone');

		function _(el){
			return document.getElementById(el);
		}

		// var displayUploads = function(data){
		// 	var uploads = document.getElementById('uploads'),
		// 	anchor,
		// 	x;

		// 	for(x=0; x<data.length; x=x+1){
		// 		anchor = document.createElement('a');
		// 		anchor.href = data[x].file;
		// 		anchor.innerText = data[x].name;

		// 		uploads.appendChild(anchor);
		// 	}
		// }
 
		var upload = function(files, token){
			// console.log(token);
			var formData = new FormData(),
			xhr  =  new XMLHttpRequest();
			
			formData.append('file', files);

			// xhr.onload = function(){
				// var data = JSON.parse(this.responseText);
				// var data = this.responseText;
				// console.log(data);
				// displayUploads(data);
			// }

			xhr.upload.addEventListener("progress", progressHandler, false);
			xhr.addEventListener("load", completeHandler, false);
			xhr.addEventListener("error", errorHandler, false);
			xhr.addEventListener("abort", abortHandler, false);
			xhr.open('post', 'http://localhost/uploadfile/welcome/upload/'+token);
			xhr.send(formData);
		}

		function progressHandler(e){
			// console.log(e);
			var idProgressBar = "progressBar"+(e.total/1000000).toFixed(2);
			var idLoaded = "loaded_n_total"+(e.total/1000000).toFixed(2);
			// console.log(e);

			_(idLoaded).innerHTML = "Uploaded "+(e.loaded/1000000).toFixed(2)+"MB bites of "+(e.total/1000000).toFixed(2)+"MB";
			var percent = (e.loaded / e.total)*100;
			_(idProgressBar).innerHTML = Math.round(percent)+"%";
			_(idProgressBar).style.width = percent+'%';
			_(idProgressBar).setAttribute('aria-valuenow', percent);
		}

		function completeHandler(e){
			var data = JSON.parse(e.target.responseText);
			var idLoaded = "loaded_n_total"+(data[0].size/1000000).toFixed(2);
			var idProgressBar = "progressBar"+(data[0].size/1000000).toFixed(2);
			console.log(data);
			_(idProgressBar).innerHTML = data[0].msg;
			_(idLoaded).innerHTML = data[0].name+" | "+(data[0].size/1000000).toFixed(2)+"MB";
		}

		function errorHandler(e){
			console.log(e);
			_(idProgressBar).innerHTML = "Upload Failed";
		}

		function abortHandler(e){
			console.log(e);
			_(idProgressBar).innerHTML = "Upload aborted"
		}

		function randomizeInteger(min, max) {
		  	if(max == null) {
		    	max = (min == null ? Number.MAX_SAFE_INTEGER : min);
		      	min = 0;
		    }

		    min = Math.ceil(min);  // inclusive min
		    max = Math.floor(max); // exclusive max

		  	if(min > max - 1) {
		    	throw new Error("Incorrect arguments.");
		    }

		    return min + Math.floor((max - min) * Math.random());
		}

		dropzone.ondrop = function(e){
			e.preventDefault();
			this.className = 'dropzone';
			var files = e.dataTransfer.files;
			// console.log(files[0]);

			for(x=0; x < files.length; x = x+1){
				var token = randomizeInteger(1000, 9999)+randomizeInteger(1000, 9999)+randomizeInteger(1000, 9999)*randomizeInteger(1000, 9999)*randomizeInteger(1000, 9999)*randomizeInteger(1000, 9999);
				upload(e.dataTransfer.files[x], token);

				_("listProgress").innerHTML +=  
	              '<div class="progress ml-5 mt-5" style="width: 85%;">'+
	            		'<div id="progressBar'+(files[x].size/1000000).toFixed(2)+'" class="progress-bar text-center" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>'+
				  '</div>'+
				  '<small id="loaded_n_total'+(files[x].size/1000000).toFixed(2)+'" class="ml-5">Uploaded 0MB bites of 0MB</small>'+
				  '<button class="btn btn-sm btn-danger rounded" onclick="hapusFile('+token+')" style="float: right; margin-top:-20px;">Hapus</button>';
			}
		}

		dropzone.ondragover = function(){
			this.className = 'dropzone dragover';
			return false;
		}

		dropzone.ondragleave = function(){
			this.className = 'dropzone';
			return false;
		}
	}());
</script>