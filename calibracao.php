<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <title>Calibração</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="js/webcam.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <form id="form" name="myForm">
      <p id="text-form">Formulário para identificação da imagens obtidas</p><br>
      <label for="name">NOME:</label><br>
      <input type="text" name="nome" id="nome" required><br><br>
      <label for="name">IDADE:</label><br>
      <input type="text" name="idade" id="idade" required><br><br>
      <label for="name">SEXO:</label><br>
      <input type="radio" id="male" name="sexo" value="male">
      <label for="male"> Masculino </label>
      <input type="radio" id="female" name="sexo" value="female">
      <label for="female"> Feminino</label><br><br>
      <label for="name">OBSERVAÇÃO:</label><br>
      <textarea name="obs" id="obs" rows="4" cols="25" required></textarea><br><br>
      <button id="btn_form" type="button" class="btn btn-primary">Enviar</button>
    </form>
    <div id="btn_video" style="display: none">
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
      <div id="div1" class="div"><img class="end" src="images/balls/1.png" id="0" onClick="changeImg('0')" /></div>
      <div id="div2" class="div"><img class="end" src="#" id="1" style="display: none" onClick="changeImg('1')" /></div>
      <div id="div3" class="div"><img class="end" src="#" id="2" style="display: none" onClick="changeImg('2')" /></div>
      <div id="div4" class="div"><img class="end" src="#" id="3" style="display: none" onClick="changeImg('3')" /></div>
      <div id="div5" class="div"><img class="end" src="#" id="4" style="display: none" onClick="changeImg('4')" /></div>
      <div id="div6" class="div"><img class="end" src="#" id="5" style="display: none" onClick="changeImg('5')" /></div>
      <div id="div7" class="div"><img class="end" src="#" id="6" style="display: none" onClick="changeImg('6')" /></div>
      <div id="div8" class="div"><img class="end" src="#" id="7" style="display: none" onClick="changeImg('7')" /></div>
      <div id="div9" class="div"><img class="end" src="#" id="8" style="display: none" onClick="changeImg('8')" /></div>

    </div>
    <?php
      $count_directory = count(scandir("js/upload/")) - 1;

      chdir( 'images/balls/' );
      $arquivos = glob("{*.png,*.jpg,*.jpeg,*.bmp,*.gif}", GLOB_BRACE);
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

      function dataAtualFormatada(){
        var data = new Date(),
            dia  = data.getDate().toString().padStart(2, '0'),
            mes  = (data.getMonth()+1).toString().padStart(2, '0'),
            ano  = data.getFullYear();
            horas = data.getHours();
            minutos = data.getMinutes();
            segundos = data.getSeconds();

        return dia+"-"+mes+"-"+ano+"-"+horas+"-"+minutos+"-"+segundos;
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

      function validateForm(nome, idade, sexo) {
        if (nome != "" || idade != "" || sexo != "" ){
          var count_directory = "<?php echo $count_directory; ?>";
          user = count_directory+"_"+dataAtualFormatada()+"_"+nome+"_"+idade+"_"+sexo
          user = user.replace(/\s/g, '');
          document.getElementById("form").style.display = "none";
          document.getElementById("btn_video").style.display = "inline";
        } else {
          alert("Preencha todos os campos");
        }
      }

      const btn_form = document.querySelector("#btn_form");
      btn_form.addEventListener("click", function() {
        nome = document.forms["myForm"]["nome"].value;
        idade = document.forms["myForm"]["idade"].value;
        sexo = document.forms["myForm"]["sexo"].value;
        obs = document.forms["myForm"]["obs"].value;
        validateForm(nome, idade, sexo);
      });

      const btn = document.querySelector("#btn");
      btn.addEventListener("click", function() {
        document.getElementById("btn_video").style.display = "none";
        document.getElementById("blocos").style.display = "inline";
        toggleFullScreen();
      });
      var end = 0;
      function changeImg(name){
        end += 1;
        document.getElementsByClassName("end").innerHTML = end;
        take_snapshot(name);
        new_block(name, end);
      }
 
      function new_block(id, end){
        document.getElementById(id).style.display = 'none';
        id = parseInt(id)
        id = id + 1
        console.log(end);
        if (end > 9){
          alert("Calibração concluída! Obrigado!!");
          window.location.href = 'video.php?user='+user+'&nome='+nome+'&idade='+idade+'&sexo='+sexo;
        } else if (id > 8){
          id = 0;
          document.getElementById(id).style.display = 'block';
          $(document.getElementById(id)).attr("src","images/balls/"+randomList().replace(/\s/g, ''));
        } else{
          id = id.toString();
          document.getElementById(id).style.display = 'block';
          $(document.getElementById(id)).attr("src","images/balls/"+randomList().replace(/\s/g, ''));
        }
      }

      <!-- Code to handle taking the snapshot and displaying it locally -->
      function take_snapshot(name) {        
      // take snapshot and get image data
        Webcam.snap( function(data_uri) {
          snapaRRAY.push(data_uri);
          // display results in page
          loadImgList();          
          uploadToserver(name);
          clearList()
        } );
      }

      function loadImgList(){
        document.getElementById('results').innerHTML=null;
        
        for (var i = snapaRRAY.length - 1; i >= 0; i--) {
          document.getElementById('results').innerHTML +='<img id="imageprev'+imgc+'" class="imgLists" src="'+snapaRRAY[i]+'"/>';
          imgc++;
        }
      }

      function clearList() {
        document.getElementById('results').innerHTML=null;
        //Webcam.reset();
        //Webcam.ShowCam();;
        snapaRRAY.length = 0;
      }

      function uploadToserver(name) {
        var imgcount = snapaRRAY.length;
        
        for (var i=0;i<imgcount; i++) {
          Webcam.upload(snapaRRAY[i], 'js/upload.php?filename='+name+'&user='+user+'&nome='+nome+'&idade='+idade+'&sexo='+sexo+'&obs='+obs, function(code, text) {
            console.log('Save successfully');
            console.log(text);
          });
          //snapaRRAY[i].remove();
        }
        //Webcam.reset();
        //Webcam.ShowCam();
        
        document.getElementById('results').innerHTML=null;
      }
        //window.onload= ShowCam();
    </script>
  </body>
</html>
