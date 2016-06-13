<?php 
// +----------------------------------------------------------------------+
// | ThinkPHP                                                             |
// +----------------------------------------------------------------------+
// | Copyright (c) 2006 liu21st.com All rights reserved.                  |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the 'License');      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// | http://www.apache.org/licenses/LICENSE-2.0                           |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an 'AS IS' BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      |
// | implied. See the License for the specific language governing         |
// | permissions and limitations under the License.                       |
// +----------------------------------------------------------------------+
// | Author: liu21st <liu21st@gmail.com>                                  |
// +----------------------------------------------------------------------+
// $Id$
import('ORG.Mail.MimePart');
/**
 +------------------------------------------------------------------------------
 * 邮件发送类
 +------------------------------------------------------------------------------
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class Mail 
{//类定义开始

    /**
    * The html part of the message
    * @var string
    */
    var $html;

    /**
    * The text part of the message(only used in TEXT only messages)
    * @private string
    */
    var $text;

    /**
    * The main body of the message after building
    * @private string
    */
    var $output;

    /**
    * An array of embedded images   /objects
    * @private array
    */
    var $html_images;

    /**
    * An array of recognised image types for the findHtmlImages() method
    * @private array
    */
    var $image_types;

    /**
    * Parameters that affect the build process
    * @private array
    */
    var $build_params;

    /**
    * Array of attachments
    * @private array
    */
    var $attachments;

    /**
    * The main message headers
    * @var array
    */
    var $headers;

    /**
    * Whether the message has been built or not
    * @var boolean
    */
    var $is_built;
    
    /**
    * The return path address. If not set the From:
    * address is used instead
    * @var string
    */
    var $return_path;
    
    /**
    * Array of information needed for smtp sending
    * @var array
    */
    var $smtp_params;

    /**
    * Sendmail path. Do not include -f
    * @var $sendmail_path
    */
    var $sendmail_path;

    /**
     +----------------------------------------------------------
     * 架构函数
     * 
     +----------------------------------------------------------
     * @static
     * @access public 
     +----------------------------------------------------------
     */
    function __construct()
    {
        $this->attachments   = array();
        $this->html_images   = array();
        $this->headers       = array();
        $this->is_built      = false;
        $this->text          = '';
        $this->sendmail_path = '/usr/sbin/sendmail -ti';
        $this->image_types = array('gif'  => 'image/gif',
                                   'jpg'  => 'image/jpeg',
                                   'jpeg' => 'image/jpeg',
                                   'jpe'  => 'image/jpeg',
                                   'bmp'  => 'image/bmp',
                                   'png'  => 'image/png',
                                   'tif'  => 'image/tiff',
                                   'tiff' => 'image/tiff',
                                   'swf'  => 'application/x-shockwave-flash');

        $this->build_params['html_encoding'] = 'quoted-printable';
        $this->build_params['text_encoding'] = '7bit';
        $this->build_params['html_charset']  = OUTPUT_CHARSET;
        $this->build_params['text_charset']  = OUTPUT_CHARSET;
        $this->build_params['head_charset']  = OUTPUT_CHARSET;
        $this->build_params['text_wrap']     = 998;

        if (!empty($_SERVER['HTTP_HOST'])) {
            $helo = $_SERVER['HTTP_HOST'];
        } 
        $this->smtp_params['host'] = 'localhost';
        $this->smtp_params['port'] = 25;
        $this->smtp_params['helo'] = $helo;
        $this->smtp_params['auth'] = false;
        $this->smtp_params['user'] = '';
        $this->smtp_params['pass'] = '';

        $this->headers['MIME-Version'] = '1.0';
        $this->headers['X-Mailer'] = 'Mail By Think<http://www.fcs.org.cn>';
    }

    /**
     +----------------------------------------------------------
     * 设置换行符号
     * SMTP 使用  \r\n 其他使用  \n
     +----------------------------------------------------------
     * @static
     * @access public 
     +----------------------------------------------------------
     * @param mixed $name 数据
     * @param string $value  数据表名
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    function setCRLF($crlf = "\n")
    {
        if (!defined('CRLF')) {
            define('CRLF', $crlf, true);
        }

        if (!defined('MAIL_MIMEPART_CRLF')) {
            define('MAIL_MIMEPART_CRLF', $crlf, true);
        }
    }

   /**
    * Accessor to set the SMTP parameters
    * 
    * @param string $host Hostname
    * @param string $port Port
    * @param string $helo HELO string to use
    * @param bool   $auth User authentication or not
    * @param string $user Username
    * @param string $pass Password
    */
    function setSMTPParams($host = null, $port = null, $helo = null, $auth = null, $user = null, $pass = null)
    {
        if (!is_null($host)) $this->smtp_params['host'] = $host;
        if (!is_null($port)) $this->smtp_params['port'] = $port;
        if (!is_null($helo)) $this->smtp_params['helo'] = $helo;
        if (!is_null($auth)) $this->smtp_params['auth'] = $auth;
        if (!is_null($user)) $this->smtp_params['user'] = $user;
        if (!is_null($pass)) $this->smtp_params['pass'] = $pass;
    }

    /**
    * Sets sendmail path and options (optionally) (when directly piping to sendmail)
    * 
    * @param string $path Path and options for sendmail command
    */
    function setSendmailPath($path)
    {
        $this->sendmail_path = $path;
    }

    /**
    * Accessor function to set the text encoding
    * 
    * @param object $encoding Text encoding to use
    */
    function setTextEncoding(iEncoding $encoding)
    {
        $this->build_params['text_encoding'] = $encoding;
    }

    /**
    * Accessor function to set the HTML encoding
    * 
    * @param object $encoding HTML encoding to use
    */
    function setHTMLEncoding(iEncoding $encoding)
    {
        $this->build_params['html_encoding'] = $encoding;
    }
    
    /**
    * Accessor function to set the text charset
    * 
    * @param string $charset Character set to use
    */
    function setTextCharset($charset = 'UTF-8')
    {
        $this->build_params['text_charset'] = $charset;
    }

    /**
    * Accessor function to set the HTML charset
    * 
    * @param string $charset Character set to use
    */
    function setHTMLCharset($charset = 'UTF-8')
    {
        $this->build_params['html_charset'] = $charset;
    }

    /**
    * Accessor function to set the header encoding charset
    * 
    * @param string $charset Character set to use
    */
    function setHeadCharset($charset = 'UTF-8')
    {
        $this->build_params['head_charset'] = $charset;
    }

    /**
    * Accessor function to set the text wrap count
    * 
    * @param integer $count Point at which to wrap text
    */
    function setTextWrap($count = 998)
    {
        $this->build_params['text_wrap'] = $count;
    }

    /**
    * Accessor to set a header
    * 
    * @param string $name  Name of header
    * @param string $value Value of header
    */
    function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
    * Accessor to add a Subject: header
    * 
    * @param string $subject Subject to set
    */
    function setSubject($subject)
    {
        $this->headers['Subject'] = $subject;
    }

    /**
    * Accessor to add a From: header
    * 
    * @param string $from From address
    */
    function setFrom($from)
    {
        $this->headers['From'] = $from;
    }
    
    /**
    * Accessor to set priority. Priority given should be either
    * high, normal or low. Can also be specified numerically, 
    * being 1, 3 or 5 (respectively).
    * 
    * @param mixed $priority The priority to use.
    */
    function setPriority($priority = 'normal')
    {
        switch (strtolower($priority)) {
            case 'high':
            case '1':
                $this->headers['X-Priority'] = '1';
                $this->headers['X-MSMail-Priority'] = 'High';
                break;

            case 'normal':
            case '3':
                $this->headers['X-Priority'] = '3';
                $this->headers['X-MSMail-Priority'] = 'Normal';
                break;

            case 'low':
            case '5':
                $this->headers['X-Priority'] = '5';
                $this->headers['X-MSMail-Priority'] = 'Low';
                break;
        }
    }

    /**
    * Accessor to set the return path
    * 
    * @param string $return_path Return path to use
    */
    function setReturnPath($return_path)
    {
        $this->return_path = $return_path;
    }

    /**
    * Accessor to add a Cc: header
    * 
    * @param string $cc Carbon Copy address
    */
    function setCc($cc)
    {
        $this->headers['Cc'] = $cc;
    }

    /**
    * Accessor to add a Bcc: header
    * 
    * @param string $bcc Blind Carbon Copy address
    */
    function setBcc($bcc)
    {
        $this->headers['Bcc'] = $bcc;
    }

    /**
    * Adds plain text. Use this function
    * when NOT sending html email
    * 
    * @param string $text Plain text of email
    */
    function setText($text)
    {
        $this->text = $text;
    }

    /**
    * Adds HTML to the emails, with an associated text part.
    * If third part is given, images in the email will be loaded
    * from this directory.
    * 
    * @param string $html       HTML part of email
    * @param string $images_dir Images directory
    */
    function setHTML($html, $images_dir = null)
    {
        $this->html = $html;

        if (!empty($images_dir)) {
            $this->findHtmlImages($images_dir);
        }
    }

    /**
    * Function for extracting images from
    * html source. This function will look
    * through the html code supplied by setHTML()
    * and find any file that ends in one of the
    * extensions defined in $obj->image_types.
    * If the file exists it will read it in and
    * embed it, (not an attachment).
    * 
    * @param string $images_dir Images directory to look in
    */
    function findHtmlImages($images_dir)
    {
        // Build the list of image extensions
        $extensions = array_keys($this->image_types);

        preg_match_all('/(?:"|\')([^"\']+\.('.implode('|', $extensions).'))(?:"|\')/Ui', $this->html, $matches);

        foreach ($matches[1] as $m) {
            if (file_exists($images_dir . $m)) {
                $html_images[] = $m;
                $this->html = str_replace($m, basename($m), $this->html);
            }
        }

        /**
        * Go thru found images
        */
        if (!empty($html_images)) {

            // If duplicate images are embedded, they may show up as attachments, so remove them.
            $html_images = array_unique($html_images);
            sort($html_images);

            foreach ($html_images as $img) {
                if ($image = file_get_contents($images_dir . $img)) {
                    $ext          = preg_replace('#^.*\.(\w{3,4})$#e', 'strtolower("$1")', $img);
                    $content_type = $this->image_types[$ext];
                    $this->addEmbeddedImage(new stringEmbeddedImage($image, basename($img), $content_type));
                }
            }
        }
    }

    /**
    * Adds an image to the list of embedded
    * images.
    * 
    * @param string $object Embedded image object
    */
    function addEmbeddedImage($embeddedImage)
    {
        $embeddedImage->cid = md5(uniqid(time()));

        $this->html_images[] = $embeddedImage;
    }


    /**
    * Adds a file to the list of attachments.
    * 
    * @param string $attachment Attachment object
    */
    function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;
    }

    /**
    * Adds a text subpart to a mime_part object
    * 
    * @param  object $obj 
    * @return object      Mime part object
    */
    function addTextPart(&$message)
    {
        $params['content_type'] = 'text/plain';
        //$params['encoding']     = $this->build_params['text_encoding']->getType();
        $params['charset']      = $this->build_params['text_charset'];

        if (!empty($message)) {
            $message->addSubpart($this->text, $params);
        } else {
            $message = new Mail_mimePart($this->text, $params);
        }
    }

    /**
    * Adds a html subpart to a mime_part object
    * 
    * @param object $obj
    * @return object     Mime part object
    */
    function addHtmlPart(&$message)
    {
        $params['content_type'] = 'text/html';
        //$params['encoding']     = $this->build_params['html_encoding']->getType();
        $params['charset']      = $this->build_params['html_charset'];

        if (!empty($message)) {
            $message->addSubpart($this->html, $params);
        } else {
            $message = new Mail_mimePart($this->html, $params);
        }
    }

    /**
    * Starts a message with a mixed part
    * 
    * @return object Mime part object
    */
    function addMixedPart(&$message)
    {
        $params['content_type'] = 'multipart/mixed';
        
        $message = new Mail_mimePart('', $params);
    }

    /**
    * Adds an alternative part to a mime_part object
    * 
    * @param  object $obj
    * @return object      Mime part object
    */
    function addAlternativePart(&$message)
    {
        $params['content_type'] = 'multipart/alternative';

        if (!empty($message)) {
            return $message->addSubpart('', $params);
        } else {
            $message = new Mail_mimePart('', $params);
        }
    }

    /**
    * Adds a html subpart to a mime_part object
    * 
    * @param  object $obj
    * @return object      Mime part object
    */
    function addRelatedPart(&$message)
    {
        $params['content_type'] = 'multipart/related';

        if (!empty($message)) {
            return $message->addSubpart('', $params);
        } else {
            $message = new Mail_mimePart('', $params);
        }
    }

    /**
    * Adds all html images to a mime_part object
    * 
    * @param  object $obj Message object
    */
    function addHtmlImageParts(&$message)
    {
        foreach ($this->html_images as $value) {
            $params['content_type'] = $value->contentType;
            $params['encoding']     = $value->encoding->getType();
            $params['disposition']  = 'inline';
            $params['dfilename']    = $value->name;
            $params['cid']          = $value->cid;
    
            $message->addSubpart($value->data, $params);
        }
    }

    /**
    * Adds all attachments to a mime_part object
    * 
    * @param object $obj Message object
    */
    function addAttachmentParts(&$message)
    {
        foreach ($this->attachments as $value) {
            $params['content_type'] = $value->contentType;
            $params['encoding']     = $value->encoding->getType();
            $params['disposition']  = 'attachment';
            $params['dfilename']    = $value->name;
        
            $message->addSubpart($value->data, $params);
        }
    }

    /**
    * Builds the multipart message.
    */
    function build()
    {
        if (!empty($this->html_images)) {
            foreach ($this->html_images as $value) {
                $quoted = preg_quote($value->name);
                $cid    = preg_quote($value->cid);

                $this->html = preg_replace("#src=\"$quoted\"|src='$quoted'#", "src=\"cid:$cid\"", $this->html);
                $this->html = preg_replace("#background=\"$quoted\"|background='$quoted'#", "background=\"cid:$cid\"", $this->html);
            }
        }

        $message     = null;
        $attachments = !empty($this->attachments);
        $html_images = !empty($this->html_images);
        $html        = !empty($this->html);
        $text        = !$html;

        switch (true) {
            case $text:
                $message = null;
                if ($attachments) {
                    $this->addMixedPart($message);
                }

                $this->addTextPart($message);

                // Attachments
                $this->addAttachmentParts($message);
                break;

            case $html AND !$attachments AND !$html_images:
                $this->addAlternativePart($message);

                $this->addTextPart($message);
                $this->addHtmlPart($message);
                break;

            case $html AND !$attachments AND $html_images:
                $this->addRelatedPart($message);
                $alt = $this->addAlternativePart($message);

                $this->addTextPart($alt);
                $this->addHtmlPart($alt);
                
                // HTML images
                $this->addHtmlImageParts($message);
                break;

            case $html AND $attachments AND !$html_images:
                $this->addMixedPart($message);
                $alt = $this->addAlternativePart($message);

                $this->addTextPart($alt);
                $this->addHtmlPart($alt);
                
                // Attachments
                $this->addAttachmentParts($message);
                break;

            case $html AND $attachments AND $html_images:
                $this->addMixedPart($message);
                $rel = $this->addRelatedPart($message);
                $alt = $this->addAlternativePart($rel);
                
                $this->addTextPart($alt);
                $this->addHtmlPart($alt);
                
                // HTML images
                $this->addHtmlImageParts($rel);
                
                // Attachments
                $this->addAttachmentParts($message);
                break;

        }

        if (isset($message)) {
            $output = $message->encode();
            $this->output   = $output['body'];
            $this->headers  = array_merge($this->headers, $output['headers']);

            // Figure out hostname
            if (!empty($_SERVER['HTTP_HOST'])) {
                $hostname = $_SERVER['HTTP_HOST'];
            
            } else if (!empty($_SERVER['SERVER_NAME'])) {
                $hostname = $_SERVER['SERVER_NAME'];
            
            } else if (!empty($_ENV['HOSTNAME'])) {
                $hostname = $_ENV['HOSTNAME'];
            
            } else {
                $hostname = 'localhost';
            }
            
            $message_id = sprintf('<%s.%s@%s>', base_convert(time(), 10, 36), base_convert(rand(), 10, 36), $hostname);
            $this->headers['Message-ID'] = $message_id;

            $this->is_built = true;
            return true;
        } else {
            return false;
        }
    }

    /**
    * Function to encode a header if necessary
    * according to RFC2047
    * 
    * @param  string $input   Value to encode
    * @param  string $charset Character set to use
    * @return string          Encoded value
    */
    function encodeHeader($input, $charset = 'UTF-8')
    {
        preg_match_all('/(\w*[\x80-\xFF]+\w*)/', $input, $matches);
        foreach ($matches[1] as $value) {
            $replacement = preg_replace('/([\x80-\xFF])/e', '"=" . strtoupper(dechex(ord("\1")))', $value);
            $input = str_replace($value, '=?' . $charset . '?Q?' . $replacement . '?=', $input);
        }
        
        return $input;
    }

    /**
    * Sends the mail.
    *
    * @param  array  $recipients Array of receipients to send the mail to
    * @param  string $type       How to send the mail ('mail' or 'sendmail' or 'smtp')
    * @return mixed
    */
    function send($recipients, $type = 'mail')
    {
        if (!defined('CRLF')) {
            $this->setCRLF( ($type == 'mail' OR $type == 'sendmail') ? "\n" : "\r\n");
        }

        if (!$this->is_built) {
            $this->build();
        }

        switch ($type) {
            case 'mail':
                $subject = '';
                if (!empty($this->headers['Subject'])) {
                    $subject = $this->encodeHeader($this->headers['Subject'], $this->build_params['head_charset']);
                    unset($this->headers['Subject']);
                }

                // Get flat representation of headers
                foreach ($this->headers as $name => $value) {
                    $headers[] = $name . ': ' . $this->encodeHeader($value, $this->build_params['head_charset']);
                }

                $to = $this->encodeHeader(implode(', ', $recipients), $this->build_params['head_charset']);

                if (!empty($this->return_path)) {
                    $result = mail($to, $subject, $this->output, implode(CRLF, $headers), '-f' . $this->return_path);
                } else {
                    $result = mail($to, $subject, $this->output, implode(CRLF, $headers));
                }

                // Reset the subject in case mail is resent
                if ($subject !== '') {
                    $this->headers['Subject'] = $subject;
                }


                // Return
                return $result;
                break;

            case 'sendmail':
                // Get flat representation of headers
                foreach ($this->headers as $name => $value) {
                    $headers[] = $name . ': ' . $this->encodeHeader($value, $this->build_params['head_charset']);
                }

                // Encode To:
                $headers[] = 'To: ' . $this->encodeHeader(implode(', ', $recipients), $this->build_params['head_charset']);

                // Get return path arg for sendmail command if necessary
                $returnPath = '';
                if (!empty($this->return_path)) {
                    $returnPath = '-f' . $this->return_path;
                }

                $pipe = popen($this->sendmail_path . " " . $returnPath, 'w');
                    $bytes = fputs($pipe, implode(CRLF, $headers) . CRLF . CRLF . $this->output);
                $r = pclose($pipe);

                return $r;
                break;

            case 'smtp':
                require_once('Smtp.class.php');
                require_once('RFC822.class.php');
                $smtp = &smtp::connect($this->smtp_params);

                // Parse recipients argument for internet addresses
                foreach ($recipients as $recipient) {
                    $addresses = RFC822::parseAddressList($recipient, $this->smtp_params['helo'], null, false);
                    foreach ($addresses as $address) {
                        $smtp_recipients[] = sprintf('%s@%s', $address->mailbox, $address->host);
                    }
                }
                unset($addresses); // These are reused
                unset($address);   // These are reused

                // Get flat representation of headers, parsing
                // Cc and Bcc as we go
                foreach ($this->headers as $name => $value) {
                    if ($name == 'Cc' OR $name == 'Bcc') {
                        $addresses = RFC822::parseAddressList($value, $this->smtp_params['helo'], null, false);
                        foreach ($addresses as $address) {
                            $smtp_recipients[] = sprintf('%s@%s', $address->mailbox, $address->host);
                        }
                    }
                    if ($name == 'Bcc') {
                        continue;
                    }
                    $headers[] = $name . ': ' . $this->encodeHeader($value, $this->build_params['head_charset']);
                }
                // Add To header based on $recipients argument
                $headers[] = 'To: ' . $this->encodeHeader(implode(', ', $recipients), $this->build_params['head_charset']);

                // Add headers to send_params
                $send_params['headers']    = $headers;
                $send_params['recipients'] = array_values(array_unique($smtp_recipients));
                $send_params['body']       = $this->output;

                // Setup return path
                if (isset($this->return_path)) {
                    $send_params['from'] = $this->return_path;
                } elseif (!empty($this->headers['From'])) {
                    $from = RFC822::parseAddressList($this->headers['From']);
                    $send_params['from'] = sprintf('%s@%s', $from[0]->mailbox, $from[0]->host);
                } else {
                    $send_params['from'] = 'postmaster@' . $this->smtp_params['helo'];
                }

                // Send it
                if (!$smtp->send($send_params)) {
                    $this->errors = $smtp->getErrors();
                    return false;
                }
                return true;
                break;
        }
    }

    /**
    * Use this method to return the email
    * in message/rfc822 format. Useful for
    * adding an email to another email as
    * an attachment. there's a commented
    * out example in example.php.
    * 
    * @param array  $recipients Array of recipients 
    * @param string $type       Method to be used to send the mail.
    *                           Used to determine the line ending type.
    */
    function getRFC822($recipients, $type = 'mail')
    {
        // Make up the date header as according to RFC822
        $this->setHeader('Date', date('D, d M y H:i:s O'));

        if (!defined('CRLF')) {
            $this->setCRLF($type == 'mail' ? "\n" : "\r\n");
        }

        if (!$this->is_built) {
            $this->build();
        }

        // Return path ?
        if (isset($this->return_path)) {
            $headers[] = 'Return-Path: ' . $this->return_path;
        }

        // Get flat representation of headers
        foreach ($this->headers as $name => $value) {
            $headers[] = $name . ': ' . $value;
        }
        $headers[] = 'To: ' . implode(', ', $recipients);

        return implode(CRLF, $headers) . CRLF . CRLF . $this->output;
    }
}//类定义结束

/**
* Attachment classes
*/
class attachment
{
    /**
    * Data of attachment
    * @var string
    */
    var $data;
    
    /**
    * Name of attachment (filename)
    * @var string
    */
    var $name;
    
    /**
    * Content type of attachment
    * @var string
    */
    var $contentType;
    
    /**
    * Encoding type of attachment
    * @var object
    */
    var $encoding;
    
    /**
    * Constructor
    * 
    * @param string $data        File data
    * @param string $name        Name of attachment (filename)
    * @param string $contentType Content type of attachment
    * @param object $encoding    Encoding type to use
    */
    function __construct($data, $name, $contentType, iEncoding $encoding)
    {
        $this->data        = $data;
        $this->name        = $name;
        $this->contentType = $contentType;
        $this->encoding    = $encoding;
    }
}


/**
* File based attachment class
*/
class fileAttachment extends attachment
{
    /**
    * Constructor
    * 
    * @param string $filename    Name of file
    * @param string $contentType Content type of file
    * @param string $encoding    What encoding to use
    */
    function __construct($filename, $contentType = 'application/octet-stream', $encoding = null)
    {
        $encoding = is_null($encoding) ? new Base64Encoding() : $encoding;

        parent::__construct(file_get_contents($filename), basename($filename), $contentType, $encoding);
    }
}


/**
* Attachment class to handle attachments which are contained
* in a variable.
*/
class stringAttachment extends attachment
{
    /**
    * Constructor
    * 
    * @param string $data        File data
    * @param string $name        Name of attachment (filename)
    * @param string $contentType Content type of file
    * @param string $encoding    What encoding to use
    */
    function __construct($data, $name = '', $contentType = 'application/octet-stream', $encoding = null)
    {
        $encoding = is_null($encoding) ? new Base64Encoding() : $encoding;
        
        parent::__construct($data, $name, $contentType, $encoding);
    }
}


/**
* File based embedded image class
*/
class fileEmbeddedImage extends fileAttachment
{
}


/**
* String based embedded image class
*/
class stringEmbeddedImage extends stringAttachment
{
}


/**
* Base64 Encoding class
*/
class Base64Encoding
{
    /*
    * Function to encode data using
    * base64 encoding.
    * 
    * @param string $input Data to encode
    */
    function encode($input)
    {
        return rtrim(chunk_split(base64_encode($input), 76, defined('MAIL_MIME_PART_CRLF') ? MAIL_MIME_PART_CRLF : "\r\n"));
    }
    
    /**
    * Returns type
    */
    function getType()
    {
        return 'base64';
    }
}


/**
* Quoted Printable Encoding class
*/
class QPrintEncoding
{
    /*
    * Function to encode data using
    * quoted-printable encoding.
    * 
    * @param string $input Data to encode
    */
    function encode($input)
    {
        // Replace non printables
        $input    = preg_replace('/([^\x20\x21-\x3C\x3E-\x7E\x0A\x0D])/e', 'sprintf("=%02X", ord("\1"))', $input);
        $inputLen = strlen($input);
        $outLines = array();
        $output   = '';

        $lines = preg_split('/\r?\n/', $input);
        
        // Walk through each line
        for ($i=0; $i<count($lines); $i++) {
            // Is line too long ?
            if (strlen($lines[$i]) > $lineMax) {
                $outLines[] = substr($lines[$i], 0, $lineMax - 1) . "="; // \r\n Gets added when lines are imploded
                $lines[$i] = substr($lines[$i], $lineMax - 1);
                $i--; // Ensure this line gets redone as we just changed it
            } else {
                $outLines[] = $lines[$i];
            }
        }
        
        // Convert trailing whitespace		
        $output = preg_replace('/(\x20+)$/me', 'str_replace(" ", "=20", "\1")', $outLines);

        return implode("\r\n", $output);
    }
    
    /**
    * Returns type
    */
    function getType()
    {
        return 'quoted-printable';
    }
}


/**
* 7Bit Encoding class
*/
class SevenBitEncoding 
{
    /*
    * Function to "encode" data using
    * 7bit encoding.
    * 
    * @param string $input Data to encode
    */
    function encode($input)
    {
        return $input;
    }
    
    /**
    * Returns type
    */
    function getType()
    {
        return '7bit';
    }
}


/**
* 8Bit Encoding class
*/
class EightBitEncoding
{
    /*
    * Function to "encode" data using
    * 8bit encoding.
    * 
    * @param string $input Data to encode
    */
    function encode($input)
    {
        return $input;
    }
    
    /**
    * Returns type
    */
    function getType()
    {
        return '8bit';
    }
}
?>