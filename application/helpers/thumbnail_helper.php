<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Thumbnail helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

if ( ! function_exists('thumb_url')) {
	function thumb_url($type = '', $filename = '', $thumb_width = 0, $thumb_height = 0, $is_create = false, $is_crop = true, $crop_mode = 'center', $is_sharpen = false, $um_value = '80/0.5/3', $create_animate_thumb = false)
	{
		if (empty($type) OR empty($filename)) {
			$filename = 'noimage.gif';
			$thumb = thumbnail(
				'',
				$filename,
				$thumb_width,
				$thumb_height,
				$is_create,
				$is_crop,
				$crop_mode,
				$is_sharpen,
				$um_value,
				$create_animate_thumb
			);
			return site_url($thumb);
		}

		$thumb = thumbnail(
			$type,
			$filename,
			$thumb_width,
			$thumb_height,
			$is_create,
			$is_crop,
			$crop_mode,
			$is_sharpen,
			$um_value,
			$create_animate_thumb
		);
		return site_url($thumb);
	}
}


// 출처 : http://www.amina.co.kr
if ( ! function_exists('thumbnail')) {
	function thumbnail($type = '', $filename = '', $thumb_width = 0, $thumb_height = 0, $is_create = false, $is_crop = true, $crop_mode = 'center', $is_sharpen = false, $um_value = '80/0.5/3', $create_animate_thumb = false)
	{
		$source_file = config_item('uploads_dir') . '/';
		if ($type) {
			$source_file .= $type . '/';
		}
		$source_file .= $filename;

		if (is_file($source_file) === false) { // 원본 파일이 없다면
			return;
		}

		if (empty($thumb_width) && empty($thumb_height)) {
			return $source_file;
		}

		$size = @getimagesize($source_file);
		if ($size[2] < 1 OR $size[2] > 3) { // gif, jpg, png 에 대해서만 적용
			return;
		}

		$uploadDir = config_item('uploads_dir') . '/cache/';
		if (is_dir($uploadDir) === false) {
			@mkdir($uploadDir, 0755);
			@chmod($uploadDir, 0755);
			$file = $uploadDir . 'index.php';
			$f = @fopen($file, 'w');
			@fwrite($f, '');
			@fclose($f);
			@chmod($file, 0644);
		}
		if ($type) {
			$uploadDir .= $type . '/';
			if (is_dir($uploadDir) === false) {
				@mkdir($uploadDir, 0755);
				@chmod($uploadDir, 0755);
				$file = $uploadDir . 'index.php';
				$f = @fopen($file, 'w');
				@fwrite($f, '');
				@fclose($f);
				@chmod($file, 0644);
			}
		}
		$exp = explode('/', $filename);
		$filepos = count($exp) - 1;
		for ($k = 0; $k < $filepos; $k++) {
			$uploadDir .= $exp[$k] . '/';
			if (is_dir($uploadDir) === false) {
				@mkdir($uploadDir, 0755);
				@chmod($uploadDir, 0755);
				$file = $uploadDir . 'index.php';
				$f = @fopen($file, 'w');
				@fwrite($f, '');
				@fclose($f);
				@chmod($file, 0644);
			}
		}

		$realfilename = $exp[$filepos];

		$target_path = $uploadDir;

		// 디렉토리가 존재하지 않거나 쓰기 권한이 없으면 썸네일 생성하지 않음
		if ( ! (is_dir($target_path) && is_writable($target_path))) {
			return '';
		}

		// Animated GIF는 썸네일 생성하지 않음
		if ($size[2] === 1) {
			if (is_animated_gif ($source_file) && $create_animate_thumb === false) {
				return $source_file;
			}
		}

		$ext = array(1 => 'gif', 2 => 'jpg', 3 => 'png');
		$thumb_filename = preg_replace("/\.[^\.]+$/i", '', $realfilename); // 확장자제거
		$thumb_file = $target_path . 'thumb-' . $thumb_filename . '_' . $thumb_width . 'x' . $thumb_height . '.' . $ext[$size[2]];

		$thumb_time = @filemtime($thumb_file);
		$source_time = @filemtime($source_file);

		if (file_exists($thumb_file)) {
			if ($is_create === false && $source_time < $thumb_time) {
				return $thumb_file;
			}
		}

		// 원본파일의 GD 이미지 생성
		$src = null;
		$degree = 0;

		if ($size[2] === 1) {
			$src = imagecreatefromgif ($source_file);
			$src_transparency = imagecolortransparent($src);
		} elseif ($size[2] === 2) {
			$src = imagecreatefromjpeg($source_file);

			if (function_exists('exif_read_data')) {
				// exif 정보를 기준으로 회전각도 구함
				$exif = @exif_read_data($source_file);
				if ( ! empty($exif['Orientation'])) {
					switch ($exif['Orientation']) {
						case 8:
							$degree = 90;
							break;
						case 3:
							$degree = 180;
							break;
						case 6:
							$degree = -90;
							break;
					}

					// 회전각도 있으면 이미지 회전
					if ($degree) {
						$src = imagerotate($src, $degree, 0);

						// 세로사진의 경우 가로, 세로 값 바꿈
						if ($degree === 90 || $degree === -90) {
							$tmp = $size;
							$size[0] = $tmp[1];
							$size[1] = $tmp[0];
						}
					}
				}
			}
		} elseif ($size[2] === 3) {
			$src = imagecreatefrompng($source_file);
			imagealphablending($src, true);
		} else {
			return;
		}

		if (empty($src)) {
			return;
		}

		$is_large = true;
		$keep_origin = false;
		// width, height 설정
		if ($thumb_width) {
			if (empty($thumb_height)) {
				$thumb_height = round(($thumb_width * $size[1]) / $size[0]);
				if ($thumb_width > $size[0]) {
					$keep_origin = true;
				}
			} else {
				if ($size[0] < $thumb_width || $size[1] < $thumb_height) {
					$is_large = false;
				}
			}
		} else {
			if ($thumb_height) {
				$thumb_width = round(($thumb_height * $size[0]) / $size[1]);
			}
		}

		$dst_x = 0;
		$dst_y = 0;
		$src_x = 0;
		$src_y = 0;
		$src_w = $size[0];
		$src_h = $size[1];
		$dst_w = $keep_origin ? $src_w : $thumb_width;
		$dst_h = $keep_origin ? $src_h : $thumb_height;

		$ratio = $dst_h / $dst_w;

		if ($is_large) {
			// 크롭처리
			if ($is_crop) {
				switch ($crop_mode) {
					case 'center':
						if ($size[1] / $size[0] >= $ratio) {
							$src_h = round($src_w * $ratio);
							$src_y = round(($size[1] - $src_h) / 2);
						} else {
							$src_w = round($size[1] / $ratio);
							$src_x = round(($size[0] - $src_w) / 2);
						}
						break;
					default:
						if ($size[1] / $size[0] >= $ratio) {
							$src_h = round($src_w * $ratio);
						} else {
							$src_w = round($size[1] / $ratio);
						}
						break;
				}
			}

			$dst = imagecreatetruecolor($dst_w, $dst_h);

			if ($size[2] === 3) {
				imagealphablending($dst, false);
				imagesavealpha($dst, true);
			} elseif ($size[2] === 1) {
				$palletsize = imagecolorstotal($src);
				if ($src_transparency >= 0 && $src_transparency < $palletsize) {
					$transparent_color = imagecolorsforindex($src, $src_transparency);
					$current_transparent = imagecolorallocate($dst, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
					imagefill($dst, 0, 0, $current_transparent);
					imagecolortransparent($dst, $current_transparent);
				}
			}
		} else {
			$dst = imagecreatetruecolor($dst_w, $dst_h);
			$bgcolor = imagecolorallocate($dst, 255, 255, 255); // 배경색

			if ($src_w < $dst_w) {
				if ($src_h >= $dst_h) {
					$dst_x = round(($dst_w - $src_w) / 2);
					$src_h = $dst_h;
				} else {
					$dst_x = round(($dst_w - $src_w) / 2);
					$dst_y = round(($dst_h - $src_h) / 2);
					$dst_w = $src_w;
					$dst_h = $src_h;
				}
			} else {
				if ($src_h < $dst_h) {
					$dst_y = round(($dst_h - $src_h) / 2);
					$dst_h = $src_h;
					$src_w = $dst_w;
				}
			}

			if ($size[2] === 3) {
				$bgcolor = imagecolorallocatealpha($dst, 0, 0, 0, 127);
				imagefill($dst, 0, 0, $bgcolor);
				imagealphablending($dst, false);
				imagesavealpha($dst, true);
			} elseif ($size[2] === 1) {
				$palletsize = imagecolorstotal($src);
				if ($src_transparency >= 0 && $src_transparency < $palletsize) {
					$transparent_color = imagecolorsforindex($src, $src_transparency);
					$current_transparent = imagecolorallocate($dst, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
					imagefill($dst, 0, 0, $current_transparent);
					imagecolortransparent($dst, $current_transparent);
				} else {
					imagefill($dst, 0, 0, $bgcolor);
				}
			} else {
				imagefill($dst, 0, 0, $bgcolor);
			}
		}

		imagecopyresampled($dst, $src, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

		// sharpen 적용
		if ($is_sharpen && $is_large) {
			$val = explode('/', $um_value);
			UnsharpMask($dst, $val[0], $val[1], $val[2]);
		}

		if ($size[2] === 1) {
			imagegif ($dst, $thumb_file);
		} elseif ($size[2] === 3) {
			$png_compress = 5;
			imagepng($dst, $thumb_file, $png_compress);
		} else {
			$jpg_quality = 90;
			imagejpeg($dst, $thumb_file, $jpg_quality);
		}

		chmod($thumb_file, 0644); // 추후 삭제를 위하여 파일모드 변경

		imagedestroy($src);
		imagedestroy($dst);

		return $thumb_file;
	}
}


// 출처 : http://vikjavev.no/computing/ump.php
if ( ! function_exists('UnsharpMask')) {
	function UnsharpMask($img, $amount, $radius, $threshold)
	{

	/*
	New:
	- In version 2.1 (February 26 2007) Tom Bishop has done some important speed enhancements.
	- From version 2 (July 17 2006) the script uses the imageconvolution function in PHP
	version >= 5.1, which improves the performance considerably.


	Unsharp masking is a traditional darkroom technique that has proven very suitable for
	digital imaging. The principle of unsharp masking is to create a blurred copy of the image
	and compare it to the underlying original. The difference in colour values
	between the two images is greatest for the pixels near sharp edges. When this
	difference is subtracted from the original image, the edges will be
	accentuated.

	The Amount parameter simply says how much of the effect you want. 100 is 'normal'.
	Radius is the radius of the blurring circle of the mask. 'Threshold' is the least
	difference in colour values that is allowed between the original and the mask. In practice
	this means that low-contrast areas of the picture are left unrendered whereas edges
	are treated normally. This is good for pictures of e.g. skin or blue skies.

	Any suggenstions for improvement of the algorithm, expecially regarding the speed
	and the roundoff errors in the Gaussian blur process, are welcome.

	 */

	////////////////////////////////////////////////////////////////////////////////////////////////
	////
	//// Unsharp Mask for PHP - version 2.1.1
	////
	//// Unsharp mask algorithm by Torstein Hønsi 2003-07.
	//// thoensi_at_netcom_dot_no.
	//// Please leave this notice.
	////
	///////////////////////////////////////////////////////////////////////////////////////////////



		// $img is an image that is already created within php using
		// imgcreatetruecolor. No url! $img must be a truecolor image.

		// Attempt to calibrate the parameters to Photoshop:
		if ($amount > 500) {
			$amount = 500;
		}
		$amount = $amount * 0.016;
		if ($radius > 50) {
			$radius = 50;
		}
		$radius = $radius * 2;
		if ($threshold > 255) {
			$threshold = 255;
		}

		$radius = abs(round($radius)); // Only integers make sense.
		if ($radius === 0) {
			return $img; imagedestroy($img);
		}
		$w = imagesx($img); $h = imagesy($img);
		$imgCanvas = imagecreatetruecolor($w, $h);
		$imgBlur = imagecreatetruecolor($w, $h);


		// Gaussian blur matrix:
		//
		// 1 2 1
		// 2 4 2
		// 1 2 1
		//
		//////////////////////////////////////////////////


		if (function_exists('imageconvolution')) { // PHP >= 5.1
			$matrix = array(
				array( 1, 2, 1 ),
				array( 2, 4, 2 ),
				array( 1, 2, 1 ),
			);
			$divisor = array_sum(array_map('array_sum', $matrix));
			$offset = 0;

			imagecopy ($imgBlur, $img, 0, 0, 0, 0, $w, $h);
			imageconvolution($imgBlur, $matrix, $divisor, $offset);
		} else {

		// Move copies of the image around one pixel at the time and merge them with weight
		// according to the matrix. The same matrix is simply repeated for higher radii.
			for ($i = 0; $i < $radius; $i++) {
				imagecopy ($imgBlur, $img, 0, 0, 1, 0, $w - 1, $h); // left
				imagecopymerge ($imgBlur, $img, 1, 0, 0, 0, $w, $h, 50); // right
				imagecopymerge ($imgBlur, $img, 0, 0, 0, 0, $w, $h, 50); // center
				imagecopy ($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h);

				imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 33.33333 ); // up
				imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 25); // down
			}
		}

		if ($threshold> 0) {
			// Calculate the difference between the blurred pixels and the original
			// and set the pixels
			for ($x = 0; $x < $w-1; $x++) { // each row
				for ($y = 0; $y < $h; $y++) { // each pixel

					$rgbOrig = ImageColorAt($img, $x, $y);
					$rOrig = (($rgbOrig >> 16) & 0xFF);
					$gOrig = (($rgbOrig >> 8) & 0xFF);
					$bOrig = ($rgbOrig & 0xFF);

					$rgbBlur = ImageColorAt($imgBlur, $x, $y);

					$rBlur = (($rgbBlur >> 16) & 0xFF);
					$gBlur = (($rgbBlur >> 8) & 0xFF);
					$bBlur = ($rgbBlur & 0xFF);

					// When the masked pixels differ less from the original
					// than the threshold specifies, they are set to their original value.
					$rNew = (abs($rOrig - $rBlur) >= $threshold)
						? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig))
						: $rOrig;
					$gNew = (abs($gOrig - $gBlur) >= $threshold)
						? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig))
						: $gOrig;
					$bNew = (abs($bOrig - $bBlur) >= $threshold)
						? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig))
						: $bOrig;



					if (($rOrig !== $rNew) || ($gOrig !== $gNew) || ($bOrig !== $bNew)) {
							$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);
							ImageSetPixel($img, $x, $y, $pixCol);
						}
				}
			}
		} else {
			for ($x = 0; $x < $w; $x++) { // each row
				for ($y = 0; $y < $h; $y++) { // each pixel
					$rgbOrig = ImageColorAt($img, $x, $y);
					$rOrig = (($rgbOrig >> 16) & 0xFF);
					$gOrig = (($rgbOrig >> 8) & 0xFF);
					$bOrig = ($rgbOrig & 0xFF);

					$rgbBlur = ImageColorAt($imgBlur, $x, $y);

					$rBlur = (($rgbBlur >> 16) & 0xFF);
					$gBlur = (($rgbBlur >> 8) & 0xFF);
					$bBlur = ($rgbBlur & 0xFF);

					$rNew = ($amount * ($rOrig - $rBlur)) + $rOrig;
					if ($rNew > 255) {
						$rNew= 255;
					} elseif ($rNew < 0) {
						$rNew= 0;
					}
					$gNew = ($amount * ($gOrig - $gBlur)) + $gOrig;
					if ($gNew > 255) {
						$gNew= 255;
					} elseif ($gNew < 0) {
						$gNew= 0;
					}
					$bNew = ($amount * ($bOrig - $bBlur)) + $bOrig;
					if ($bNew>255) {
						$bNew= 255;
					} elseif ($bNew < 0) {
						$bNew= 0;
					}
					$rgbNew = ($rNew << 16) + ($gNew <<8) + $bNew;
					ImageSetPixel($img, $x, $y, $rgbNew);
				}
			}
		}
		imagedestroy($imgCanvas);
		imagedestroy($imgBlur);

		return true;

	}
}


// 출처 : http://www.php.net/manual/en/function.imagecreatefromgif.php#104473
if ( ! function_exists('is_animated_gif')) {
	function is_animated_gif ($filename)
	{
		if ( ! ($fh = @fopen($filename, 'rb'))) {
			return false;
		}
		$count = 0;
		// an animated gif contains multiple "frames", with each frame having a
		// header made up of:
		// * a static 4-byte sequence (\x00\x21\xF9\x04)
		// * 4 variable bytes
		// * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)

		// We read through the file til we reach the end of the file, or we've found
		// at least 2 frame headers
		while ( ! feof($fh) && $count < 2) {
			$chunk = fread($fh, 1024 * 100); //read 100kb at a time
			$count += preg_match_all(
				'#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s',
				$chunk,
				$matches
			);
		}

		fclose($fh);
		return $count > 1;
	}
}
