<?php
/*
	
	�û������ϴ����޶��ļ���С30kb���ļ�����jpg ,png ,�������30kb�����������ͼ

*/
class Upfile{
    var $dir;             //�����������Ŀ¼
    var $filepath;         //����Ŀ¼����·��
    var $filetype;         //�ļ�����
    var $fileext;          //�ļ���չ��
    var $time;             //�Զ����ļ��ϴ�ʱ��
    var $allow_types;     //�����ϴ���������
    var $field;             //�ϴ��ؼ�����
    var $maxsize;         //��������ļ���С����λΪKB
    var $thumb_width=0;    //����ͼ���
    var $thumb_height=0;   //����ͼ�߶�
    var $watermark_file; //ˮӡͼƬ��ַ
    var $watermark_pos;  //ˮӡλ��
    var $watermark_trans;//ˮӡ͸����
	var $pinzi;
	var $imgType;
	var $src_x = 0;
	var $src_y = 0;
	var $dst_x = 0;
	var $dst_y = 0;
	
    //���캯��
    //$types : �����ϴ����ļ����� , $maxsize : �����С ,  $field : �ϴ��ؼ����� , $time : �Զ����ϴ�ʱ��
    function __construct($types = 'jpg|png|gif', $maxsize = 30, $field = 'attach', $time = '') {
        $this->allow_types = explode('|',$types);
        $this->maxsize = $maxsize * 1024;
        $this->field = $field;
        $this->time = $time ? $time : time();
		$this->pinzi = 95;
    }
	//�����ϴ���Ϊ��ͼ
	function setSizeBig($maxsize,$img_type){
		$this->maxsize = $maxsize;
		$this->imgType = $img_type;
	}
    //���ò������ļ������ŵ�Ŀ¼
    //$basedir  : ��Ŀ¼������Ϊ����·��
    //$filedir  : �Զ�����Ŀ¼�����ò���{y}��{m}��{d}
    function set_dir($basedir='',$filedir = '') {
    	//echo $basedir;
        $this->filepath = UPLOAD_URL.$basedir;
        $dir = UPLOAD_ROOT.$basedir;
        !is_dir($dir) && @mkdir($dir,0777);
        if (!empty($filedir)) {
            $filedir = str_replace(array('{y}','{m}','{d}'),array(date('Y',$this->time),date('m',$this->time),date('d',$this->time)),strtolower($filedir));
            $dirs = explode('/',$filedir);
            foreach ($dirs as $d) {
                !empty($d) && $dir .= $d.'/';
                $this->filepath.=$d.'/';
                !is_dir($dir) && @mkdir($dir,0777);
            }
        }
        $this->dir = $dir;
    }
    //ͼƬ����ͼ���ã��������������ͼ��������
    //$width : ����ͼ��� , $height : ����ͼ�߶�
    function set_thumb ($width = 0, $height = 0) {
        $this->thumb_width  = $width;
        $this->thumb_height = $height;
    }
    //ͼƬˮӡ���ã�������������ˮӡ��������
    //$file : ˮӡͼƬ , $pos : ˮӡλ�� , $trans : ˮӡ͸����
    function set_watermark ($file, $pos = 6, $trans = 80) {
        $this->watermark_file  = $file;
        $this->watermark_pos   = $pos;
        $this->watermark_trans = $trans;
    }
	
	function set_thumb_height($height = 0){
		$this->thumb_height = $height;
	}
    /*----------------------------------------------------------------
    ִ���ļ��ϴ��������귵��һ�������ϴ��ɹ���ʧ�ܵ��ļ���Ϣ���飬
    ���У�name Ϊ�ļ������ϴ��ɹ�ʱ���ϴ����������ϵ��ļ������ϴ�ʧ�����Ǳ��ص��ļ���
          dir  Ϊ�������ϴ�Ÿø���������·�����ϴ�ʧ�ܲ����ڸ�ֵ
          size Ϊ������С���ϴ�ʧ�ܲ����ڸ�ֵ
          flag Ϊ״̬��ʶ��1��ʾ�ɹ���-1��ʾ�ļ����Ͳ�����-2��ʾ�ļ���С����
    -----------------------------------------------------------------*/
    function execute() {
        $files = array(); //�ɹ��ϴ����ļ���Ϣ
        $field = $this->field;
        $keys = array_keys($_FILES[$field]['name']);
        foreach ($keys as $key) {		
            if (!$_FILES[$field]['name'][$key]) continue;
			$img_info = getimagesize($_FILES[$field]['tmp_name'][$key]);  // 3������ֵ width height type  ����type�����ж������ͼ�mita����

            $fileext = $this->fileext($_FILES[$field]['name'][$key]); //��ȡ�ļ���չ��
            $filename = $this->time.mt_rand(100,999).'.'.$fileext; //�����ļ���
            $filedir = $this->dir;    //����ʵ�ʴ��Ŀ¼
            $filesize = $_FILES[$field]['size'][$key]; //�ļ���С
            //�ļ����Ͳ�����
            if ($img_info[2]==''){
                $files[$key]['name'] = $_FILES[$field]['name'][$key];
                $files[$key]['flag'] = -1;
                continue;
            }
            //�ļ���С����
			if ($filesize > $this->maxsize){
				// �����ȴ�ͼ���
				if($this->imgType=='big'){
					// �޶��߶����
					if(intval($this->thumb_height)!=0){
						
					}else{
						// δ�޶��߶��Ȳ�����
						$files[$key]['name'] = $_FILES[$field]['name'][$key];
						$files[$key]['flag'] = -2;
						continue;
					}
				}else{
				// ����ͼСͼ���
					// ��������ȴ���500���д�С����
					if($img_info[0]>400){
						$this->thumb_width = 400;// ���������С���������
						$this->pinzi = 90;
					}else{
						$this->thumb_width = $img_info[0];
						$this->pinzi = 90;
					}
				}
			}
            //--
            $files[$key]['filename'] = $filename;
            $files[$key]['filetype'] = $_FILES[$field]['type'][$key];
            $files[$key]['filesize'] = $filesize;
            $files[$key]['filepath'] = $this->filepath;
            $files[$key]['fileext'] = $fileext;
            //�����ϴ��ļ���ɾ����ʱ�ļ�
            if (is_uploaded_file($_FILES[$field]['tmp_name'][$key])) {
                move_uploaded_file($_FILES[$field]['tmp_name'][$key],$filedir.$filename);
                @unlink($_FILES[$field]['tmp_name'][$key]);
                $files[$key]['flag'] = 1;
                //��ͼƬ���м�ˮӡ����������ͼ
                if (in_array($fileext,array('jpg','png','gif'))) {
					// ���������ˣ����������˿�Ȼ�߶ȣ���������ԣ�����ͼ��
                    if ($this->thumb_width!=0||$this->thumb_height!=0) {
						// �û��������֣��趨�˸߶�
						if($this->thumb_height!=0){
							$this->dst_x = 0;
							$this->dst_y = 0;
							$this->src_x = 0;
							$this->src_y = round(($img_info[1]-$this->thumb_height)/2);
						}
						// ֱ���滻ԭͼ��������С
//return $this->create_thumb($filedir.$filename,$filedir.$filename); // dubug
						if ($this->create_thumb($filedir.$filename,$filedir.$filename)) {
                            $files[$key]['thumb'] = $filename;
                        }
                        $files[$key]['isthumb'] = 1;
						
						if($fileext=='png'){
							$filename = explode(".",$filename);
							$filename = $filename[0].".jpg";
						}
                    }
                    //$this->create_watermark($filedir.$filename); // �Ȳ���ˮӡ
                }
            }
            $files[$key]['filepath'].=$filename;
        }
        return $files;
    }
    //��������ͼ,����ͬ����չ����������ͼ
    //$src_file : ��Դͼ��·�� , $thumb_file : ����ͼ·��
    function create_thumb ($src_file,$thumb_file) {
        $t_width  = $this->thumb_width;
        $t_height = $this->thumb_height;
        if (!file_exists($src_file)) return false;
        $src_info = getImageSize($src_file);
        //�����Դͼ��С�ڻ��������ͼ�򿽱�Դͼ����Ϊ����ͼ
        if ($src_info[0] <= $t_width && $src_info[1] <= $t_height && $this->pinzi==95) {
            if (!copy($src_file,$thumb_file)) {
                return false;
            }
            return true;
        }
        //��������������ͼ��С
        /*if ($src_info[0] - $t_width > $src_info[1] - $t_height) {
            $t_height = ($t_width / $src_info[0]) * $src_info[1];
        } else {
            $t_width = ($t_height / $src_info[1]) * $src_info[0];
        }*/
		if($this->imgType=='big'){
			$t_width = $src_info[0];
			$t_height = $this->thumb_height;
			$src_info[1] = $this->thumb_height;
		}else{
			$t_height = ($t_width / $src_info[0]) * $src_info[1]; //�߶��ɿ�Ⱦ���
		}
        //ȡ���ļ���չ��
        $fileext = $this->fileext($src_file);
        switch ($fileext) {
            case 'jpg' :
                $src_img = ImageCreateFromJPEG($src_file); break;
            case 'png' :
                $src_img = ImageCreateFromPNG($src_file); break;
            case 'gif' :
                $src_img = ImageCreateFromGIF($src_file); break;
        }
        //����һ�����ɫ������ͼ��
        $thumb_img = @ImageCreateTrueColor($t_width,$t_height);
        //ImageCopyResampled����������ͼ��ƽ���ȽϺã����ȿ���
        if (function_exists('imagecopyresampled')) {
            @ImageCopyResampled($thumb_img,$src_img,$this->dst_x,$this->dst_y,$this->src_x,$this->src_y,$t_width,$t_height,$src_info[0],$src_info[1]);
        } else {
            @ImageCopyResized($thumb_img,$src_img,$this->dst_x,$this->dst_y,$this->src_x,$this->src_y,$t_width,$t_height,$src_info[0],$src_info[1]);
        }
        //��������ͼ
        switch ($fileext) {
            case 'jpg' :
                ImageJPEG($thumb_img,$thumb_file,$this->pinzi); break;
            case 'gif' :
                ImageGIF($thumb_img,$thumb_file); break;
            case 'png' :
				unlink($thumb_file);
				$thumb_file = str_replace("png","jpg",$thumb_file);
				ImageJPEG($thumb_img,$thumb_file,$this->pinzi);
			break;
                //ImagePNG($thumb_img,$thumb_file); break;
        }
        //������ʱͼ��
        @ImageDestroy($src_img);
        @ImageDestroy($thumb_img);
        return true;
    }
    //ΪͼƬ���ˮӡ
    //$file : Ҫ���ˮӡ���ļ�
    function create_watermark ($file) {
        //�ļ��������򷵻�
        if (!file_exists($this->watermark_file) || !file_exists($file)) return;
        if (!function_exists('getImageSize')) return;

        //���GD֧�ֵ��ļ�����
        $gd_allow_types = array();
        if (function_exists('ImageCreateFromGIF')) $gd_allow_types['image/gif'] = 'ImageCreateFromGIF';
        if (function_exists('ImageCreateFromPNG')) $gd_allow_types['image/png'] = 'ImageCreateFromPNG';
        if (function_exists('ImageCreateFromJPEG')) $gd_allow_types['image/jpeg'] = 'ImageCreateFromJPEG';
        //��ȡ�ļ���Ϣ
        $fileinfo = getImageSize($file);
        $wminfo   = getImageSize($this->watermark_file);
        if ($fileinfo[0] < $wminfo[0] || $fileinfo[1] < $wminfo[1]) return;
        if (array_key_exists($fileinfo['mime'],$gd_allow_types)) {
            if (array_key_exists($wminfo['mime'],$gd_allow_types)) {

                //���ļ�����ͼ��
                $temp = $gd_allow_types[$fileinfo['mime']]($file);
                $temp_wm = $gd_allow_types[$wminfo['mime']]($this->watermark_file);
                //ˮӡλ��
                switch ($this->watermark_pos) {
                    case 1 :  //��������
                        $dst_x = 0; $dst_y = 0; break;
                    case 2 :  //��������
                        $dst_x = ($fileinfo[0] - $wminfo[0]) / 2; $dst_y = 0; break;
                    case 3 :  //��������
                        $dst_x = $fileinfo[0]; $dst_y = 0; break;
                    case 4 :  //�ײ�����
                        $dst_x = 0; $dst_y = $fileinfo[1]; break;
                    case 5 :  //�ײ�����
                        $dst_x = ($fileinfo[0] - $wminfo[0]) / 2; $dst_y = $fileinfo[1]; break;
                    case 6 :  //�ײ�����
                        $dst_x = $fileinfo[0]-$wminfo[0]; $dst_y = $fileinfo[1]-$wminfo[1]; break;
                    default : //���
                        $dst_x = mt_rand(0,$fileinfo[0]-$wminfo[0]); $dst_y = mt_rand(0,$fileinfo[1]-$wminfo[1]);
                }
                if (function_exists('ImageAlphaBlending')) ImageAlphaBlending($temp_wm,True); //�趨ͼ��Ļ�ɫģʽ
                if (function_exists('ImageSaveAlpha')) ImageSaveAlpha($temp_wm,True); //���������� alpha ͨ����Ϣ
                //Ϊͼ�����ˮӡ
                if (function_exists('imageCopyMerge')) {
                    ImageCopyMerge($temp,$temp_wm,$dst_x,$dst_y,0,0,$wminfo[0],$wminfo[1],$this->watermark_trans);
                } else {
                    ImageCopyMerge($temp,$temp_wm,$dst_x,$dst_y,0,0,$wminfo[0],$wminfo[1]);
                }
                //����ͼƬ
                switch ($fileinfo['mime']) {
                    case 'image/jpeg' :
                        @imageJPEG($temp,$file);
                        break;
                    case 'image/png' :
                        @imagePNG($temp,$file);
                        break;
                    case 'image/gif' :
                        @imageGIF($temp,$file);
                        break;
                }
                //������ʱͼ��
                @imageDestroy($temp);
                @imageDestroy($temp_wm);
            }
        }
    }
    //��ȡ�ļ���չ��
    function fileext($filename) {
        return strtolower(substr(strrchr($filename,'.'),1,10));
    }
	
	/*  */
	function execute2() {
        $files = array(); //�ɹ��ϴ����ļ���Ϣ
        $field = 'upload';
        //$keys = array_keys($_FILES[$field]['name']);
        //foreach ($keys as $key) {
            //if (!$_FILES[$field]['name'][$key]) continue;
			$img_info = getimagesize($_FILES[$field]['tmp_name']);  // 3������ֵ width height type  ����type�����ж������ͼ�mita����

            $fileext = $this->fileext($_FILES[$field]['name']); //��ȡ�ļ���չ��
            $filename = $this->time.mt_rand(100,999).'.'.$fileext; //�����ļ���
            $filedir = $this->dir;    //����ʵ�ʴ��Ŀ¼
            $filesize = $_FILES[$field]['size']; //�ļ���С
            //�ļ����Ͳ�����
            //�ļ����Ͳ�����
            if ($img_info[2]==''){
                $files['name'] = $_FILES[$field]['name'];
                $files['flag'] = -1;
                return;
            }
            //�ļ���С����
			if ($filesize > $this->maxsize) {
				$files['name'] = $_FILES[$field]['name'];
				$files['flag'] = -2;
				 return;
			}
            //--
            $files['filename'] = $filename;
           // $files['dir'] = $filedir;
            $files['filetype']=$_FILES[$field]['type'];
            $files['filesize'] = $filesize;
            $files['filepath'] = $this->filepath;
            $files['fileext'] = $fileext;
            //�����ϴ��ļ���ɾ����ʱ�ļ�
            if (is_uploaded_file($_FILES[$field]['tmp_name'])) {
                move_uploaded_file($_FILES[$field]['tmp_name'],$filedir.$filename);
                @unlink($_FILES[$field]['tmp_name']);
                $files['flag'] = 1;
                //��ͼƬ���м�ˮӡ����������ͼ
                if (in_array($fileext,array('jpg','png','gif'))) {
                    if ($this->thumb_width) {
                        if ($this->create_thumb($filedir.$filename,$filedir.'thumb_'.$filename)) {
                            $files['thumb'] = 'thumb_'.$filename;  //����ͼ�ļ���
                        }
                        $files['isthumb'] = 1;
                    }
                    $this->create_watermark($filedir.$filename);
                }
            }
             $files['filepath'] .= $this->upload_files.'/'.$filename;
        //}
        return $files;
    }
}
?>