<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <title>Calibração</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="James Dias">
    <script type="text/javascript" src="js/webcam.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <div id="btn_video">
      <div class="container">
        <p>Ajuste sua face no vídeo abaixo e depois clique no botão tela cheia para iniciar o processo de capitura de imagens.</p><br>
        <div id="my_camera"></div>
        <div class="controller" style="display: none">
          <div id="results" ></div>
        </div>
        <button id="btn" type="button" class="btn btn-primary">Tela cheia</button>
      </div>
    </div>
    <div id="blocos" style="display: none">
      <div id="form">
        <video class="video_web" id="video1">
          <source src="videos/1.mp4" type="video/mp4">
        </video>
      </div>
    </div>

    <div id="video" style="display: none">
      <div>
          <h2>Camera Preview</h2>
          <video id="cameraPreview" width="960" height="720" autoplay></video>
          <p>
              <button id="startButton" onclick="startCapture();">Start Capture</button>
              <button id="stopButton" onclick="stopCapture();">Stop Capture</button>
          </p>
      </div>

      <div>
          <h2>Processing Preview</h2>
          <canvas id="processingPreview" width="960" height="720"></canvas>
      </div>

      <div>
          <h2>Recording Preview</h2>
          <video id="recordingPreview" width="960" height="720" autoplay controls></video>
          <p>
              <a id="downloadButton">Download</a>
          </p>
      </div>
    </div>
    <div id="aguarde" style="display: none">
      <p><strong>ATENÇÃO:</strong></p>
      <p><strong>Aguarde um momento!</strong></p>
      <p><strong>Processamento em andamento!!!</strong></p>
    </div>
    <?php
      $count_directory = count(scandir("js/upload/")) - 1;

      chdir( 'videos/' );
      $arquivos = glob("{*.mp4}", GLOB_BRACE);
    ?>

    <script language="JavaScript">
        Webcam.set({
          width: 1280,
          height: 720,
          image_format: 'jpeg',
          jpeg_quality: 90
        });
        Webcam.attach( '#my_camera' );
        var snapaRRAY=[];
        var imgc=0;
        nome = "<?php print $_GET['nome']; ?>";
        idade = "<?php print $_GET['idade']; ?>";
        sexo = "<?php print $_GET['sexo']; ?>";
        user = "<?php print $_GET['user']; ?>";

      console.log("nome", nome);
        function randomList(){
            var arquivos = "<?php foreach($arquivos as $img) echo $img."; " ?>";
            var nameList = arquivos.split(";");
            random = Math.floor(Math.random() * nameList.length-2);
            random = ((random >= 0) ? random : Math.floor(Math.random() * nameList.length-2));
            random = ((random >= 0) ? random : Math.floor(Math.random() * nameList.length-2));
            random = ((random >= 0) ? random : Math.floor(Math.random() * nameList.length-2));
            random = ((random >= 0) ? random : Math.floor(Math.random() * nameList.length-2));
            random = ((random >= 0) ? random : Math.floor(Math.random() * nameList.length-2));
            random = ((random >= 0) ? random : Math.floor(Math.random() * nameList.length-2));        
            random = ((random >= 0) ? random : 0);
            lista = nameList[random];
            return lista;
        }

      function toggleFullScreen() {
        if (!document.fullscreenElement &&    // alternative standard method
            !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {  // current working methods
          if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
          } else if (document.documentElement.msRequestFullscreen) {
            document.documentElement.msRequestFullscreen();
          } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
          } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
          }
        } else {
          if (document.exitFullscreen) {
            document.exitFullscreen();
          } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
          } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
          } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
          }
        }
      }

      const btn = document.querySelector("#btn");
      btn.addEventListener("click", function() {
        document.getElementById("btn_video").style.display = "none";
        document.getElementById("blocos").style.display = "inline";
        let video = window.document.querySelector("#video1");
        video.play();
        Webcam.reset();
        toggleFullScreen();        
        startCapture();
        timedText();
      });

      function timedText() {
        setTimeout(myTimeoutFim, 30000)
        setTimeout(fim, 60000)
      }
      function fim() {
        alert("Processamento concluído! Obrigado!!");
        document.getElementById("aguarde").style.display = "none";
        alert("Acabou");
        //location.reload()
      }
      
      function myTimeoutFim(){
        document.getElementById("video1").style.display = 'none';
        stopCapture();
        document.getElementById("blocos").style.display = "none";
        document.getElementById("aguarde").style.display = "block";
      }
        
      // Config vídeo
      const ROI_X = 250;
      const ROI_Y = 150;
      const ROI_WIDTH = 240;
      const ROI_HEIGHT = 180;
      
      const FPS = 25;
      
      let cameraStream = null;
      let processingStream = null;
      let mediaRecorder = null;
      let mediaChunks = null;
      let processingPreviewIntervalId = null;

      function processFrame() {
          let cameraPreview = document.getElementById("cameraPreview");
          
          processingPreview
              .getContext('2d')
              .drawImage(cameraPreview, 0, 0, 960, 720);
      }

      function generateRecordingPreview() {
        let mediaBlob = new Blob(mediaChunks, { type: "video\/mp4" });
        let mediaBlobUrl = URL.createObjectURL(mediaBlob);

        //$.post("js/upload.php", "file="+mediaBlob+"&time_temp="+new Date().getTime(), function( data ) {
        //  console.log(data);
        //});

        var fd = new FormData();
        fd.append('nome', nome);
        fd.append('idade', idade);
        fd.append('sexo', sexo);
        fd.append('user', user);
        fd.append('data', mediaBlob);
        $.ajax({
            type: 'POST',
            url: 'js/new_upload.php',
            data: fd,
            processData: false,
            contentType: false
        }).done(function(data) {
              console.log(data);
        });


        let recordingPreview = document.getElementById("recordingPreview");
        recordingPreview.src = mediaBlobUrl;

        let downloadButton = document.getElementById("downloadButton");
        downloadButton.href = mediaBlobUrl;
        downloadButton.download = "RecordedVideo.webm";
      }
          
      function startCapture() {
          const constraints = { video: true, audio: false };
          navigator.mediaDevices.getUserMedia(constraints)
          .then((stream) => {
              cameraStream = stream;
              
              let processingPreview = document.getElementById("processingPreview");
              processingStream = processingPreview.captureStream(FPS);
              
              mediaRecorder = new MediaRecorder(processingStream);
              mediaChunks = []
              
              mediaRecorder.ondataavailable = function(event) {
                  mediaChunks.push(event.data);
                  if(mediaRecorder.state == "inactive") {
                      generateRecordingPreview();
                  }
              };
              
              mediaRecorder.start();
              
              let cameraPreview = document.getElementById("cameraPreview");
              cameraPreview.srcObject = stream;
          
              processingPreviewIntervalId = setInterval(processFrame, 1000 / FPS);
          })
          .catch((err) => {
              alert("No media device found!");
          });
      };
      
      function stopCapture() {
          if(cameraStream != null) {
              cameraStream.getTracks().forEach(function(track) {
                  track.stop();
              });
          }
          
          if(processingStream != null) {
              processingStream.getTracks().forEach(function(track) {
                  track.stop();
              });
          }
          
          if(mediaRecorder != null) {
              if(mediaRecorder.state == "recording") {
                  mediaRecorder.stop();
              }
          }
          
          if(processingPreviewIntervalId != null) {
              clearInterval(processingPreviewIntervalId);
              processingPreviewIntervalId = null;
          }
      };
    </script>
  </body>
</html>