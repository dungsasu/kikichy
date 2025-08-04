<?php

namespace App\Traits;


use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager as Image;
use Intervention\Image\Drivers\Gd\Driver;

use PhpParser\Node\Expr\FuncCall;

// configure with favored image driver (gd by default)

trait CommonFunctionTrait
{
    public function parseString($str)
    {
        $arr = array("&ldquo;", "&rdquo;", "&lsquo;", "&rsquo;", "&quot;", "'", "&gt;", "&lt;");
        $str = str_replace($arr, "-", $str);
        $arr = array(".", "!", "~", "@", "#", "$", "%", "^", "&", "*", "(", ")", "=", "+", "|", "\\", "/", "?", ",", "'", '"', '“', '”', '>', '<', 'quot;');
        $str = str_replace($arr, "", $str);
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);

        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);

        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);


        $str = str_replace(" ", "-", str_replace("&*#39;", "", $str));
        return $str;
    }
    public function generate_alias($str)
    {
        // Chuyển đổi UTF-8 sang ASCII
        $parseString = $this->parseString($str);

        // Loại bỏ các ký tự không phải chữ cái hoặc số
        $unaccentedString = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $parseString));

        // Hiển thị kết quả
        return $unaccentedString;
    }
    public function upload(UploadedFile $file, $resizes = [], $path)
    {
        // $imageName = $file->getClientOriginalName();
        // $destinationPath = public_path($path);
        // $file->move($destinationPath, $imageName);
        // Image::configure(['driver' => 'gd']);
        // $res = Image::make($destinationPath . '/' . $imageName);
        // $res->save($destinationPath . '/' . $imageName);

        // return $path . '/' . $imageName;

        $imageName = $file->getClientOriginalName();
        $destinationPath = public_path($path);
        $file->move($destinationPath, $imageName);
        Image::configure(['driver' => 'gd']);

        $res = Image::make($destinationPath . '/' . $imageName);

        // Save the original image
        $res->save($destinationPath . '/' . $imageName);

        // Create a new filename with the .webp extension
        $webpImageName = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

        // Save the image as webp
        $res->encode('webp')->save($destinationPath . '/' . $webpImageName);

        if (!empty($resizes)) {
            $resizedImageName = pathinfo($imageName, PATHINFO_FILENAME) . '_resized.' . pathinfo($imageName, PATHINFO_EXTENSION);
            foreach ($resizes as $resize) {
                $res->resize($resize[0], $resize[1]);
                $res->save($destinationPath . '/' . $resizedImageName);
            }
        }
        return $path . '/' . $imageName;
    }

    public function upload2($imagePath, $resizes = [], $path, $image_name = '')
    {
        $manager = new Image(new Driver());

        $destinationPath = public_path($path);
        $imageName = basename($imagePath);

        if (!@file_exists($destinationPath)) {
            if (!mkdir($destinationPath, 0755, true) && !is_dir($destinationPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $destinationPath));
            }
        }

        $fullImagePath = public_path($imagePath);
        if (!@file_exists($fullImagePath)) {
            return $imagePath;
        }

        $extension = strtolower(pathinfo($fullImagePath, PATHINFO_EXTENSION));
        if ($extension == 'svg') {
            return $imagePath;
        }

        try {
            $res = $manager->read($fullImagePath);
        } catch (\Exception $e) {
            // Nếu không đọc được file ảnh, trả về đường dẫn gốc
            return $imagePath;
        }


        if (!@file_exists($destinationPath  . '/' . 'original_' . $imageName)) {
            $res->save($destinationPath  . '/' . 'original_' . $imageName);
        }

        $webpImageName = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

        if (!file_exists($destinationPath  . '/' . $webpImageName)) {
            // Resize theo docs mới
            $res->resize(
                !empty($resizes) ? $res->width() : intval($res->width() / 1.5),
                null,
                function ($constraint) {
                    $constraint->aspectRatio();
                }
            );

            $res->toWebp(75)->save($destinationPath  . '/' . $webpImageName);
        }

        foreach ($resizes as $key => $resize) {
            if (!$image_name) {
                $resizedImageName = "{$key}_" . pathinfo($imageName, PATHINFO_FILENAME) . "." . pathinfo($imageName, PATHINFO_EXTENSION);
            } else {
                $resizedImageName = $image_name;
            }
            $resized = $manager->read(public_path($imagePath));

            // Resize theo docs mới
            $resized->resize($resize[0], $resize[1], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $resizedWebpImageName = "{$key}_" . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
            if (!file_exists($destinationPath  . '/' . $resizedWebpImageName)) {
                $resized->toWebp(75)->save($destinationPath . '/' . $resizedWebpImageName);
            }
        }
        return $path  . '/' . $webpImageName;
    }

    public function deleteDuplicateFiles($imagePath, $path)
    {
        $destinationPath = public_path($path);
        $imageName = basename($imagePath);
        $originalImageName = 'original_' . $imageName;
        $webpImageName = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

        // Xóa file gốc trùng tên
        if (file_exists($destinationPath . '/' . $originalImageName)) {
            unlink($destinationPath . '/' . $originalImageName);
        }

        // Xóa file webp trùng tên
        if (file_exists($destinationPath . '/' . $webpImageName)) {
            unlink($destinationPath . '/' . $webpImageName);
        }

        // Xóa các file resized trùng tên
        $files = glob($destinationPath . '/*_' . pathinfo($imageName, PATHINFO_FILENAME) . '.*');
        foreach ($files as $file) {
            unlink($file);
        }

        return $path . '/' . $webpImageName;
    }

    protected static function translate($text)
    {
        return \App\Providers\TranslationServiceProvider::getCustomTranslation($text);
    }

    public function generate_menu_header_html($items, $level = 0, &$images = [])
    {
        $html = '';

        foreach ($items as $item) {
            if ($item['published']) {
                if (isset($item['hot']) && $item['hot']) {
                    $images[$level][] = $item['image'];
                }

                $color = isset($item['color_code']) && $item['color_code'] ? "color: " . $item['color_code'] : '';
                $fontweight = isset($item['bold']) && $item['bold'] ? "fw-bold" : '';
                $hasChildren = isset($item['children']) && !empty($item['children']) ? 'has-children' : '';

                $html .= '<div class="box-service level-' . $level . ' ' . $hasChildren . '">';
                $html .= '<a class="title-menu-' . $level . ' ' . $fontweight . ' " title="' . $item['text'] . '" style="' . @$color . '" href="' . $item['href'] . '" >';
                if ($item['image']) {
                    $html .= '<img src="' . $item['image'] . '" alt="' . $item['text'] . '" class="img-fluid" />';
                }
                $html .= htmlspecialchars($this->translate($item['text']));
                $html .= '</a>';

                if (isset($item['children']) && !empty($item['children'])) {
                    $html .= '<div class="level-' . $level . '-wrapper">';
                    $html .= '<div class="expand-title fw-bold fs-6 mb-3">' . @$item['summary'] . '</div>';
                    $html .= '<div class="expand-items-wrapper">';
                    foreach ($item['children'] as $child) {
                        $levelChild = $level + 1;
                        $html .= '
                            <a class="title-menu-' . $levelChild . '" title="' . $child['text'] . '" href="' . $child['href'] . '" >
                                <img src="' . $child['image'] . '" alt="' . $child['text'] . '" class="img-fluid" />
                                ' . htmlspecialchars($this->translate($child['text'])) . '
                            </a>
                        ';
                    }
                    $html .= '</div>';
                    $html .= '<hr />';
                    $html .= '<div class="expand-overflow">';
                    $html .= '<div class="expand-level-' . $level . '">';
                    $html .= $this->generate_menu_html($item['children'], $level + 1);
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</div>';
                }

                $html .= '</div>';
            }
        }

        return $html;
    }

    public function generate_menu_footer_html($menu)
    {
        $html = "";
        $items = json_decode(@$menu->description, true);
        $html .= $this->generate_menu_footer_list($items);
        return $html;
    }

    public function generate_menu_footer_list($items, $level = 0)
    {
        if (empty($items)) {
            return '';
        }

        $html = '<ul class="footer-menu-list level-' . $level . '">';

        foreach ($items as $item) {
            if (isset($item['published']) && $item['published']) {
                $color = isset($item['color_code']) && $item['color_code'] ? "color: " . $item['color_code'] : '';
                $fontweight = isset($item['bold']) && $item['bold'] ? "fw-bold" : '';
                
                $html .= '<li class="footer-menu-item level-' . $level . '">';
                $html .= '<a class="footer-menu-link ' . $fontweight . '" title="' . $item['text'] . '" style="' . $color . '" href="' . $item['href'] . '">';
                $html .= htmlspecialchars($this->translate($item['text']));
                $html .= '</a>';

                // Nếu có children, tạo submenu
                if (isset($item['children']) && !empty($item['children'])) {
                    $html .= $this->generate_menu_footer_list($item['children'], $level + 1);
                }

                $html .= '</li>';
            }
        }

        $html .= '</ul>';
        return $html;
    }

    function generate_menu_html($items, $level = 0, &$images = [])
    {
        $html = '';

        foreach ($items as $item) {
            if ($item['published']) {
                // dd($item);
                if (isset($item['hot']) && $item['hot']) {

                    $images[$level][] = $item['image'];
                }
                // dd($item['children']);

                $color = isset($item['color_code']) && $item['color_code'] ? "color: " . $item['color_code'] : '';
                $fontweight = isset($item['bold']) && $item['bold'] ? "fw-bold" : '';
                $hasChildren = isset($item['children']) && !empty($item['children']) ? 'has-children' : '';

                $html .= '<div class="box-service level-' . $level . ' ' . $hasChildren . '">';
                $html .= '
                    <a class="title-menu-' . $level . '" ' . $fontweight . ' " title="' . $item['text'] . '" style="' . @$color . '" href="' . $item['href'] . '" >
                        ' . htmlspecialchars($this->translate($item['text'])) . '
                    </a>
                ';

                if (isset($item['children']) && !empty($item['children'])) {
                    $html .= '<div class="expand-level-' . $level . '">';
                    $html .= $this->generate_menu_html($item['children'], $level + 1);
                    $html .= '</div>';
                }

                $html .= '</div>';
            }
        }

        return $html;
    }

    function findHotImages($data, $parentText = '', $rootId = null, $parentHref = '')
    {
        $hot_images = [];

        foreach ($data as $item) {
            $currentParentText = isset($item['text']) ? $item['text'] : $parentText;
            $currentRootId = $rootId ?? (isset($item['text']) ? $item['text'] : null);
            $currentParentHref = isset($item['href']) ? $item['href'] : $parentHref;

            if (isset($item['children'])) {
                $hot_images = array_merge($hot_images, $this->findHotImages($item['children'], $currentParentText, $currentRootId, $currentParentHref));
            }

            if (isset($item['hot']) && $item['hot'] == "1" && isset($item['image'])) {
                $hot_images[] = [
                    'parent_root' => $currentRootId,
                    'parent_text' => $currentParentText,
                    'parent_href' => $currentParentHref,
                    'image' => $item['image']
                ];
            }
        }
        return $hot_images;
    }

    public function generate_menu_mobile_html($items, $level = 0)
    {
        $html = '';
        foreach ($items as $i => $item) {
            if (isset($item['published']) && $item['published']) {
                $htmlButtonExpand = '';
                $htmlExpandWrapper = '';
                $htmlExpand = '';
                if (!empty($item['children'])) {
                    $htmlButtonExpand = '
                        <a class="mm-collapse" data-bs-toggle="collapse" href="#mm-' . $level . $i . '" role="button" aria-expanded="false" aria-controls="mm-' . $level . $i . '">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15 7.75833L10.8838 11.8745C10.3977 12.3606 9.60227 12.3606 9.11616 11.8745L5 7.75833" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    ';
                    $htmlExpand = $this->generate_menu_mobile_html($item['children'], $level + 1);
                    $htmlExpandWrapper = '
                        <div class="collapse" id="mm-' . $level . $i . '">
                            <div class="mm-expand-body mm-expand-body-' . $level . '">
                                ' . $htmlExpand . ' 
                            </div>
                        </div>
                    ';
                }

                $html .= '
                    <div class="mm-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="' . $item['href'] . '" title="' . $item['text'] . '">
                                ' . $item['text'] . '
                            </a>
                            ' . $htmlButtonExpand . '
                        </div>
                        ' .  $htmlExpandWrapper . '
                    </div>
                ';
            }
        }
        return $html;
    }

    public static function buildMenu($items, $level = 0)
    {
        $html = '';
        if ($level == 1) {
            $html .= '<span class="menu-wrapper menu-wrapper-' . $level . ' ">';
        }
        $html .= '<ul class="main-menu level-' . $level . '">';

        foreach ($items as $item) {
            if (isset($item['published']) && $item['published']) {
                $html .= '<li>';
                $href = htmlspecialchars($item['href']);

                if (isset($item['bold']) && $item['bold']) {
                    $html .= '<a class="fw-bold title-level-' . $level . '"  href="' . $href . '">' . htmlspecialchars(self::translate($item['text'])) . '</a>';
                } else {
                    $html .= '<a class="title-level-' . $level . '"  href="' . $href . '">' . htmlspecialchars(self::translate($item['text'])) . '</a>';
                }
                if (!empty($item['children'])) {
                    if ($level == 0) {
                        $html .= '<span class="child-indicator">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                        </span>';
                    }
                    $html .= self::buildMenu($item['children'], $level + 1);
                }
                $html .= '</li>';
            }
        }

        if ($level == 1) {
            $html .= '</ul>';
            $html .= '</span>';
        } else {
            $html .= '</ul>';
        }

        return $html;
    }

    public function format_money($value)
    {
        if ($value != 0) {
            return number_format($value, 0, ',', '.') . ' ₫';
        } else {
            return null;
        }
    }

    public function remove_fomart_money($value)
    {
        return $value ? preg_replace('/\D/', '', $value) : 0;
        // return str_replace(['.', ',', ' ', ' đ', ' vnđ', ' vnd', '₫', 'đ', 'vnđ', 'vnd'], '', $value);
    }
}
