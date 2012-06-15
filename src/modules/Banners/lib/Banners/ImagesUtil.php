<?php
/* Copied from Articles Module */
class Banners_ImagesUtil
{
    /**
     * Upload one image
     * @param $files_key        string  the key to use to get uploaded image from $_FILES superglobal.
     * @param $store_dir        string  where to store the uploaded image.
     * @param $current_image    string  the current image of the item (optional).
     * @return string           image name.
     */
    public static function uploadImage($files_key, $store_dir, $current_image)
    {
        // get service manager
        $sm = ServiceUtil::getManager();

        // get request object
        $request = $sm->getService('request');

        // folder permissions
        $folder_perms = $sm['system.chmod_dir'];

        // create store dir if not exists
        if (!file_exists($store_dir)) {
            mkdir($store_dir, $folder_perms, true);
        }

        // check if store dir is writable
        $store_dir_writable = is_writable($store_dir);
        if (!$store_dir_writable) {
            die($store_dir . ' is not writable');
        }

        // get data from FILES
        $data = $request->files->get($files_key, null);

        if (!empty($data['name'])) {

            // do some sanitation here for name
            $name = $data['name'];

            $type = $data['type'];
            $tmp_name = $data['tmp_name'];
            $error = $data['error'];
            $size = $data['size'];

            if ($size != 0 && strpos($type, "image") !== false) {
                
                // lowercase picture's extension
                $img_name = FileUtil::stripExtension($name);
                $img_extension = strtolower(FileUtil::getExtension($name));
                $name = rand(10000, 99999) . '_' . $img_name . '.' . $img_extension;
                    
                $move_uploaded_file = move_uploaded_file($tmp_name, $store_dir . '/' .$name);

                if (!$move_uploaded_file) {
                    die('Could not upload image ' . $name);
                }
                
                Banners_ImagesUtil::deleteImage($current_image);

                // image was uploaded successfully
                $image = $name;
            }
        }

        return $image;
    }
    
    /**
     * Delete an image of a banner
     * @param $image        string  the name of the image to delete.
     * @return void
     */
    public static function deleteImage($image)
    {
        if (empty($image)) {
            return;
        }
        
        $images_dir = ModUtil::getVar('Banners', 'storagedir');
        
        if (file_exists($images_dir . '/' . $image)) {
            unlink($images_dir . '/' . $image);
        }
    }
}