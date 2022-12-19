<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageService
{
    /**
     * Hàm nhận vào file ảnh, tên thư mục đích lưu ảnh
     * Thực hiện chuyển ảnh từ thư mục tạm sang thư mục đích
     * @param UploadedFile $image
     * @param string $dest_folder
     * @return string
     */
    public function create(UploadedFile $image, string $dest_folder)
    {
        // TODO: Implement moveImage() method.
        $image_name = time() . '_' . $image->getClientOriginalName();
        $image->move('upload/images/' . $dest_folder, $image_name);

        return $image_name;
    }

    /**
     * Hàm nhận vào tên ảnh cũ, thư mục lưu ảnh cũ
     * Thực hiện xóa ảnh cũ
     * @param string $dest_folder
     * @param string $old_image
     * @return void
     */
    public function delete(string $old_image, string $dest_folder)
    {
        try {
            $old_image_path = public_path() . '/' . 'upload' . '/' .  'images' . '/' . $dest_folder . '/' . $old_image;
            unlink($old_image_path);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}