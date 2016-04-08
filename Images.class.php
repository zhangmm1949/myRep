<?php
//公共函数库
/**
 * 等比缩放函数(以保存的方式实现)
 * @param string $picname 被缩放的处理图片源
 * @param int $maxx 缩放后图片的最大宽度
 * @param int $maxy 缩放后图片的最大高度
 * @param string $pre 缩放后图片名的前缀名
 * @return string 返回后的图片名称(带路径)，如a.jpg =>s_a.jpg
 */
 function imageUpdateSize($picname, $maxx=100, $maxy=100, $pre="s_")
 {
	$info = getimagesize($picname);  //获取图像源的基本信息
	/*
	echo "<pre>";
	var_dump($info);
	echo "</pre>";
	*/
	$w = $info[0]; //获取宽度
	$h = $info[1]; //获取高度
	//获取图片的类型并为此创建对应的图片源
	//（我的理解：imagecreatefromXXX就相当于读取图片的像素信息
	//到内存中，可以认为在内存中生成了原始的图片，也就是说只有
	//图片的信息在内存中才可以利用其像素信息生成新图片，处于硬
	//盘上的图片是无法拿到像素信息来直接使用的；此时在内存中的
	//图片就是内存中一块数据，由一个变量指示，此处为$im，不存在
	//文件的概念）
	switch($info[2])
	{
		case 1: //gif
			$im = imagecreatefromgif($picname);
			break;
		case 2: //jpg
			$im = imagecreatefromjpeg($picname);
			break;
		case 3: //
			$im = imagecreatefrompng($picname);
			break;
		default:
			die("图片类型错误");
	}
	//计算缩放比例
	if(($maxx/$w)>($maxy/$h))
	{
		$b = $maxy/$h;
	}else{
		$b = $maxx/$w;
	}
	//计算出缩放后的尺寸
	$nw = floor($w*$b);
	$nh = floor($h*$b);
	//创建一个新的图片源(目标图像)（一个空白的画板）
	$nim = imagecreatetruecolor($nw,$nh);
	//执行等比缩放(执行完后$nim代表的图片应该也是在内存中的，$nim就
	//代表内存中的一块区域，还没有图片文件的概念)
	imagecopyresampled($nim, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);
	//输出图像(根据源图像的类型，输出为对应的类型，涉及到文件名和路径)
	$picinfo = pathinfo($picname); //获取原图的路径信息和名字
	$newpicname = $picinfo['dirname']."/".$pre.$picinfo["basename"];
	switch($info[2])
	{
		case 1:
			imagegif($nim, $newpicname);
			break;
		case 2:
			imagejpeg($nim, $newpicname);
			break;
		case 3:
			imagepng($nim, $newpicname);
			break;
	}
	//释放图片资源（内存中资源要释放）
	imagedestroy($im);
	imagedestroy($nim);
	//返回结果（返回新图像的名称就可以引用新图像了）
	return $newpicname;
 }

 
//对方法imageUpdateSize()测试
//echo imageUpdateSize("./1365331697976.jpg");
 
 
 /**
 * 为一张图片添加一个logo图片水印(以保存的方式实现)
 * @param string $picname 被缩放的处理图片源
 * @param string $logo 水印图片 
 * @param string $pre 处理后图片名的前缀名
 * @return string 返回后的图片名称(带路径)，如a.jpg =>n_a.jpg
 */
 function imageAddLogo($picname, $logo, $pre="n_")
 {
	$picnameinfo = getimagesize($picname);
	$logoinfo = getimagesize($logo);
		
	//获取图片的类型并为此创建对应的图片源
	switch($picnameinfo[2])
	{
		case 1: //gif
			$im = imagecreatefromgif($picname);
			break;
		case 2: //jpg
			$im = imagecreatefromjpeg($picname);
			break;
		case 3: //
			$im = imagecreatefrompng($picname);
			break;
		default:
			die("图片类型错误");
	}
	switch($logoinfo[2])
	{
		case 1: //gif
			$logoim = imagecreatefromgif($logo);
			break;
		case 2: //jpg
			$logoim = imagecreatefromjpeg($logo);
			break;
		case 3: //
			$logoim = imagecreatefrompng($logo);
			break;
		default:
			die("图片类型错误");
	}
	
	//执行图片(理解这里参数的意义)
	imagecopyresampled($im, $logoim, $picnameinfo[0]-$logoinfo[0], $picnameinfo[1]-$logoinfo[1], 0, 0, $logoinfo[0], $logoinfo[1], $logoinfo[0], $logoinfo[1]);
	//输出图像
	$picinfo = pathinfo($picname); //获取原图的路径信息和名字
	$newpicname = $picinfo['dirname']."/".$pre.$picinfo["basename"];
	switch($picnameinfo[2])
	{
		case 1:
			imagegif($im, $newpicname);
			break;
		case 2:
			imagejpeg($im, $newpicname);
			break;
		case 3:
			imagepng($im, $newpicname);
			break;
	}
	//释放图片资源（内存中资源要释放）
	imagedestroy($im);
	imagedestroy($logoim);
	//返回结果（返回新图像的名称就可以引用新图像了）
	return $newpicname;
 }
 
 //对方法imageAddLogo()测试
 echo imageAddLogo("./MMM.jpeg", "./logowater.png");
