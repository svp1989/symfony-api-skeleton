<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadLimits
{
    protected $file;

    protected $maxFileSize;

    protected $mimeTypes;

    /**
     * @param UploadedFile $file
     */
    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
        $this->initialize();
    }

    public function initialize(): void
    {
        $this->maxFileSize = 0;     // если 0, то значение берется из php.ini
        $this->mimeTypes = array(); // если список пуст, то разрешены любые типы файлов
    }

    /**
     * @return integer
     */
    public function getMaxFilesize(): int
    {
        if ($this->maxFileSize)
        {
            return min(
                $this->getMaxFilesize(),
                $this->file->getMaxFilesize()
            );
        }

        return $this->file->getMaxFilesize();
    }

    /**
     * @return array
     */
    public function getMimeTypes(): array
    {
        return $this->mimeTypes;
    }
}
