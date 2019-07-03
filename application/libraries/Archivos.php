<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Archivos{
		
		public $CI;
		
		public function __construct(){
			
			$this->CI = & get_instance();
			$this->CI->load->library('image_lib');
			$this->CI->load->library('upload');
		}
		

		public function guardarArchivo($carpeta,$imagen,$allowed,$name_original=FALSE,$redimensionar=FALSE){
            $config['upload_path'] = $carpeta;
            $config['allowed_types'] = $allowed;
            $config['max_size'] = '2000';
 
            $config['file_name'] = md5(time()."".mt_rand(0, 99999));
            $this->CI->upload->initialize($config);
            $nombre_imagen = $imagen['name'];
 
            #print_r($imagen);
  
            $_FILES['userfile']['name'] = $imagen['name'];
            $_FILES['userfile']['type'] = $imagen['type'];
            $_FILES['userfile']['tmp_name'] = $imagen['tmp_name'];
            $_FILES['userfile']['error'] = $imagen['error'];
            $_FILES['userfile']['size'] = $imagen['size'];
                          
            if(!$this->CI->upload->do_upload()){

	            $error = array('error' => $this->CI->upload->display_errors());
				
	            return array("success" => FALSE, "error" => $error, "tipo" => $_FILES['userfile']['type']);
				
	        }else{
	
	            $info_imagen = $this->CI->upload->data();
	            $info_imagen['ruta_real'] = $carpeta ."/".$info_imagen['file_name'];
	            
	            if($redimensionar){
					$redimension = $this->redimensionarImagen($carpeta,$info_imagen['file_name']);
		            return array("success" => true, "info_data" => $info_imagen, "original_name"=>$name_original, "redimension" => $redimension);
	            }else
	            	return array("success" => true, "info_data" => $info_imagen, "original_name"=>$name_original);
	        }
        }

		public function redimensionarImagen($ruta,$imagen,$opc=TRUE){

        	list($ancho, $alto) = @getimagesize($ruta."/".$imagen);

        	if($ancho > 950 OR $alto > 670){
					
				//-------------------------------------------------- Configuracion para imagen web
				
	        	$config['image_library'] = 'gd2';
				$config['source_image'] = $ruta."/".$imagen;
				$config['new_image'] = $ruta."/".$imagen;
				$config['master_dim'] = 'auto';
				$config['quality'] = '100%';
				$config['maintain_ratio'] = TRUE;
				$config['width'] = $ancho;
				$config['height'] = $alto;
				
				$this->CI->image_lib->initialize($config); // Inicializar config si la imagen es mas grande de 950 X 670
					
				if(!$this->CI->image_lib->resize()){ // Si falla la redimension para web
				
					return array("success" => FALSE, "msj" => $this->CI->image_lib->display_errors());
				
				}else{
					
					$movil = $this->redimensionarImagenMovil($ruta, $imagen, $opc);
					
					return $movil;
				}
				
			}else{
				
				if($ancho > 296 OR $alto > 296)
					return $this->redimensionarImagenMovil($ruta, $imagen, $opc);
				else{
					if(!copy($ruta."/".$imagen,$ruta."/movil/"."movil_".$imagen))
						return array("success" => FALSE, "msj" => "Ocurrio un error al crear la imagen para movil");	
					else{
						if($opc===TRUE)
							$recorteGlobo = $this->recorteGloboKnyou($ruta."/movil/"."movil_".$imagen,$ruta);
						return array("success" => TRUE, "msj" => "Sin redimensionar", "url_movil" => $ruta."/movil/"."movil_".$imagen);	
					}
				}	
			}	
        }

		private function redimensionarImagenMovil($ruta,$imagen,$opc){
			
			list($ancho_n, $alto_n) = getimagesize($ruta."/".$imagen);
					
			if($ancho_n > $alto_n){
				$x = ($ancho_n/2) - ($alto_n/2);
				$y = 0;
				$l = $alto_n;
			}
			
			else if($ancho_n < $alto_n){
				$x = 0;
				$y = ($alto_n/2) - ($ancho_n/2);
				$l = $ancho_n;
			}
			
			else{
				$x = 0;
				$y = 0;
				$l = $ancho_n;
			}
			
			$config['source_image'] = $ruta."/".$imagen;
			$config['new_image'] = $ruta."/movil/"."movil_".$imagen;
			$config['quality'] = '75%';
			$config['width'] = $l;
			$config['height'] = $l;
			
			$config['x_axis'] = $x;
			$config['y_axis'] = $y;
			$config['maintain_ratio'] = FALSE;
			
			$this->CI->image_lib->clear();
			$this->CI->image_lib->initialize($config); // Inicializar config de corte para movil
			
			if(!$this->CI->image_lib->crop()){ // Si falla el corte para movil
			
				return array("success" => FALSE, "msj" => $this->CI->image_lib->display_errors());
			
			}else{ // redimension para movil correcta
				
				$config['source_image'] = $ruta."/movil/"."movil_".$imagen;
				$config['new_image'] = $ruta."/movil/"."movil_".$imagen;
				$config['quality'] = '75%';
				$config['master_dim'] = 'auto';
				$config['width'] = 296;
                $config['height'] = 296;
				$config['maintain_ratio'] = TRUE;
				
				$this->CI->image_lib->clear();
				$this->CI->image_lib->initialize($config); // Inicializar config de redimension para movil
				
				if(!$this->CI->image_lib->resize()) // Si falla redimension de 296 x 296 para moviles
					return array("success" => FALSE, "msj" => $this->CI->image_lib->display_errors());
				else{
					if($opc===TRUE)
						$recorteGlobo = $this->recorteGloboKnyou($ruta."/movil/"."movil_".$imagen,$ruta);
					else
						$recorteGlobo = FALSE;
					return array("success" => TRUE, "msj" => "Imagen redimensionada", "url_movil" => $ruta."/movil/"."movil_".$imagen,"globo" => $recorteGlobo);	
				}	
			}
		}
		
		public function recortarImagen($idRegistro = NULL,$tipo = NULL, $idFotografia,$fotografia,$width,$height,$x_axis,$y_axis){
				
			//if($idRegistro === NULL)
			//	$idRegistro = $this->session->userdata("idUsuario");
				
			//$idFotografia = $this->security->xss_clean($this->input->post("fotografia"));
			//$fotografia = $this->mdlpicturesuser->obtenerFotografiaPorId($idRegistro,$idFotografia);
			//var_dump($fotografia);
			if($fotografia !== FALSE){
					
				$config['source_image'] = $fotografia[0]->url_archivo;
				$config['new_image'] = $fotografia[0]->url_movil;
				$config['quality'] = '75%';
				$config['width'] = $width;
				$config['height'] = $height;
				$config['x_axis'] = $x_axis;
				$config['y_axis'] = $y_axis;
				$config['maintain_ratio'] = FALSE;
				
				$rutaRecorte = explode("/",$fotografia[0]->url_archivo);
				array_pop($rutaRecorte);
				$rutaRecorte = implode("/",$rutaRecorte);
		
				$this->CI->image_lib->clear();
				$this->CI->image_lib->initialize($config); // Inicializar config de corte para movil
		
				if(!$this->CI->image_lib->crop()){ // Si falla el corte para movil
		
					//print json_encode(array("success" => FALSE, "msj" => $this->CI->image_lib->display_errors()));
					return array("success" => FALSE, "msj" => $this->CI->image_lib->display_errors());
		
				}else{ // redimension para movil correcta
						
					$config['source_image'] =  $fotografia[0]->url_movil;
					$config['new_image'] =  $fotografia[0]->url_movil;
					$config['quality'] = '75%';
					$config['master_dim'] = 'auto';
					$config['width'] = 296;
					$config['height'] = 296;
					$config['maintain_ratio'] = FALSE;
						//var_dump($config);
					$this->CI->image_lib->clear();
					$this->CI->image_lib->initialize($config); // Inicializar config de redimension para movil
						
					if(!$this->CI->image_lib->resize()) // Si falla redimension de 296 x 296 para moviles
						return array("success" => FALSE, "msj" => $this->CI->image_lib->display_errors());
					else{
						//print json_encode(array("success" => TRUE, "msj" => "Imagen redimensionada"));
						$recorteGlobo = $this->recorteGloboKnyou($fotografia[0]->url_movil,$rutaRecorte);
						return array("success" => TRUE, "msj" => "Imagen redimensionada");
					}	
				}
			}
		}
		
		public function recorteGloboKnyou($foto,$ruta){
			
			if(exif_imagetype($foto) == IMAGETYPE_GIF){
				$picture = imagecreatefromgif($foto);
				$tipo = "gif";
			}	
			 	
			elseif(exif_imagetype($foto) == IMAGETYPE_PNG){
				$picture = imagecreatefrompng($foto);
				$tipo = "png";
			}	
			
		 	elseif(exif_imagetype($foto) == IMAGETYPE_JPEG){
		 		$picture = imagecreatefromjpeg($foto);
		 		$tipo = "jpg";
		 	}	
		 	
		 	else
		 		return FALSE;
		 	
		 	$nombre = explode("/",$foto);
		 	$nombre = array_pop($nombre);
		 	$nombre = (strpos($nombre,"movil") === FALSE) ? "globo_".$nombre : str_replace("movil_","globo_",$nombre);
		 	$nombre = explode(".",$nombre);
			
			$mask = imagecreatefrompng(base_url('css/globoMascara2.png'));
			
			$xSize = imagesx( $picture );
			$ySize = imagesy( $picture );
			$newPicture = imagecreatetruecolor( $xSize, $ySize );
			imagesavealpha( $newPicture, true );
			imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );
			
			if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
				$tempPic = imagecreatetruecolor( $xSize, $ySize );
				imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
				imagedestroy( $mask );
				$mask = $tempPic;
			}
			
			for( $x = 0; $x < $xSize; $x++ ) {
				for( $y = 0; $y < $ySize; $y++ ) {
					$alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );
					$alpha = 127 - floor( $alpha[ 'red' ] / 2 );
					$color = imagecolorsforindex( $picture, imagecolorat( $picture, $x, $y ) );
					imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $alpha ) );
				}
			}
			
			$rutaGlobo = $ruta."/".$nombre[0].".png";

			if(imagepng($newPicture,$rutaGlobo,9)){
				imagedestroy($newPicture);
				return $rutaGlobo;
			}
			
			return FALSE;
		}
		
		
	}
	
?>