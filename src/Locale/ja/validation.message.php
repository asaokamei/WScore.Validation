<?php

/**
 * フィルターごとのエラーメッセージ設定
 */

use WScore\Validator\Filters\ConfirmWith;
use WScore\Validator\Filters\ValidateMatch;
use WScore\Validator\Filters\Required;
use WScore\Validator\Filters\StringLength;
use WScore\Validator\Filters\ValidateDateTime;
use WScore\Validator\Filters\ValidateFloat;
use WScore\Validator\Filters\ValidateInteger;
use WScore\Validator\Filters\ValidateUtf8String;
use WScore\Validator\Locale\Messages;

return [
    // a fall back error message.
    Messages::class => "入力内容を確認してください。",

    // fail for invalid charset string.
    ValidateUtf8String::ERROR_INVALID_CHAR => '不正な文字列です。',
    ValidateUtf8String::ERROR_ARRAY_INPUT => '入力が配列です。',
    ValidateInteger::class => '整数を入力してください。',
    ValidateFloat::class => '数値を入力してください。',
    ValidateDateTime::class => '日付と認識できません。',

    // error messages for StringLength.
    StringLength::LENGTH => "文字数は {length} 文字としてください。",
    StringLength::MAX => "文字数は {max} 文字までで入力してください。",
    StringLength::MIN => "文字数は {min} 文字以上で入力してください。",

    // error messages for Match
    ValidateMatch::IP => '正しいIPアドレスを入力してください。',
    ValidateMatch::EMAIL => '正しいメールアドレスを入力してください。',
    ValidateMatch::URL => '正しいURLを入力してください。',
    ValidateMatch::MAC => '正しいMACアドレスを入力してください。',

    // required value.
    Required::class => "必須項目です。",

    // ConfirmWith
    ConfirmWith::ERROR_MISSING => '確認用の項目に入力してください。',
    ConfirmWith::ERROR_DIFFER => '確認用の項目と異なります。',
];