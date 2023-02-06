<?php
declare(strict_types=1);

namespace ZipStream;

class DeflateStream extends Stream
{
    protected $filter;

    /**
     * @var Option\File
     */
    protected $options;

    /**
     * Rewind stream
     */
    public function rewind()
    {
        // deflate filter needs to be removed before rewind
        if ($this->filter) {
            $this->removeDeflateFilter();
            $this->seek(0);
            $this->addDeflateFilter($this->options);
        } else {
            rewind($this->stream);
        }
    }

    /**
     * Remove the deflate filter
     */
    public function removeDeflateFilter()
    {
        if (!$this->filter) {
            return;
        }
        stream_filter_remove($this->filter);
        $this->filter = null;
    }

    /**
     * Add a deflate filter
     *
     * @param Option\File $options
     */
    public function addDeflateFilter(Option\File $options)
    {
        $this->options = $options;
        // parameter 4 for stream_filter_append expects array
        // so we convert the option object in an array
        $optionsArr = [
            'comment' => $options->getComment(),
            'method' => $options->getMethod(),
            'deflateLevel' => $options->getDeflateLevel(),
            'time' => $options->getTime()
        ];
        $this->filter = stream_filter_append(
            $this->stream,
            'zlib.deflate',
            STREAM_FILTER_READ,
            $optionsArr
        );
    }
}