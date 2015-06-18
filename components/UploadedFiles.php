<?php

namespace deka6pb\autoparser\components;

use yii\web\UploadedFile;

class UploadedFiles extends UploadedFile {
    /**
     * Saves the uploaded file.
     * Note that this method uses php's move_uploaded_file() method. If the target file `$file`
     * already exists, it will be overwritten.
     * @param string $file the file path used to save the uploaded file
     * @param boolean $deleteTempFile whether to delete the temporary file after saving.
     * If true, you will not be able to save the uploaded file again in the current request.
     * @return boolean true whether the file is saved successfully
     * @see error
     */
    public function saveAs($file, $deleteTempFile = true) {
        if (is_uploaded_file($file)) {
            if ($this->error == UPLOAD_ERR_OK) {
                if ($deleteTempFile) {
                    return move_uploaded_file($this->tempName, $file);
                } elseif (is_uploaded_file($this->tempName)) {
                    return copy($this->tempName, $file);
                }
            }
        } else {
            return copy($this->tempName, $file);
        }

        return false;
    }
}
