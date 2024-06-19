<?php

return [
    'default' => ['id', 'class', 'title', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'style'],

    'custom' => [
        'a' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'href', 'target', 'rel', 'download', 'hreflang', 'type'],
        'audio' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'src', 'controls', 'autoplay', 'loop', 'muted', 'preload'],
        'button' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'type', 'name', 'value', 'disabled', 'autofocus', 'form'],
        'canvas' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'width', 'height'],
        'form' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'action', 'method', 'enctype', 'autocomplete', 'novalidate', 'target', 'accept-charset'],
        'iframe' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'src', 'width', 'height', 'name', 'sandbox', 'allow', 'allowfullscreen', 'srcdoc', 'referrerpolicy', 'loading'],
        'img' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'src', 'alt', 'width', 'height', 'loading', 'srcset', 'sizes'],
        'input' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'type', 'name', 'value', 'placeholder', 'required', 'readonly', 'disabled', 'maxlength', 'minlength', 'min', 'max', 'step', 'pattern', 'autocomplete', 'autofocus', 'form', 'list', 'multiple'],
        'label' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'for'],
        'link' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'rel', 'href', 'type', 'media', 'hreflang', 'sizes'],
        'meta' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'name', 'content', 'http-equiv', 'charset'],
        'script' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'src', 'type', 'async', 'defer', 'crossorigin', 'integrity'],
        'select' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'name', 'multiple', 'required', 'size', 'disabled', 'autofocus', 'form'],
        'textarea' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'name', 'rows', 'cols', 'placeholder', 'required', 'readonly', 'disabled', 'maxlength', 'minlength', 'autocomplete', 'autofocus', 'form', 'wrap'],
        'video' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'src', 'controls', 'autoplay', 'loop', 'muted', 'preload', 'width', 'height', 'poster'],
        'blockquote' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'cite'],
        'cite' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'title'],
        'del' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'cite', 'datetime'],
        'details' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'open'],
        'embed' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'src', 'type', 'width', 'height'],
        'fieldset' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'disabled', 'form', 'name'],
        'ins' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'cite', 'datetime'],
        'map' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'name'],
        'object' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'data', 'type', 'width', 'height', 'form', 'name'],
        'ol' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'reversed', 'start', 'type'],
        'optgroup' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'disabled', 'label'],
        'option' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'disabled', 'label', 'selected', 'value'],
        'output' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'for', 'form', 'name'],
        'param' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'name', 'value'],
        'progress' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'value', 'max'],
        'q' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'cite'],
        'source' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'src', 'type', 'srcset', 'sizes', 'media'],
        'time' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'datetime'],
        'track' => ['id', 'class', 'title', 'data-*', 'lang', 'dir', 'translate', 'hidden', 'tabindex', 'accesskey', 'draggable', 'spellcheck', 'contenteditable', 'default', 'kind', 'label', 'src', 'srclang'],
    ],
];
