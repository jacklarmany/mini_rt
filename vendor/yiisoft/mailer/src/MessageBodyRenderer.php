<?php

declare(strict_types=1);

namespace Yiisoft\Mailer;

use RuntimeException;
use Throwable;
use Yiisoft\View\View;

use function html_entity_decode;
use function is_array;
use function is_string;
use function preg_match;
use function preg_replace;
use function strip_tags;
use function trim;

use const ENT_HTML5;
use const ENT_QUOTES;

/**
 * View renderer used to compose message body.
 */
final class MessageBodyRenderer
{
    /**
     * @var View The view instance.
     */
    private View $view;

    /**
     * @var MessageBodyTemplate The message body template instance.
     */
    private MessageBodyTemplate $template;

    /**
     * @param View $view The view instance.
     * @param MessageBodyTemplate $template The message body template instance.
     */
    public function __construct(View $view, MessageBodyTemplate $template)
    {
        $this->view = $view;
        $this->template = $template;
    }

    /**
     * Adds the rendered body to the message and returns it.
     *
     * @param MessageInterface $message The message to which the body will be added.
     * @param mixed $view The view to be used for rendering the message body.
     * This can be:
     * - a string, which represents the view name for rendering the HTML body of the email.
     *   In this case, the text body will be generated by applying `strip_tags()` to the HTML body.
     * - an array with 'html' and/or 'text' elements. The 'html' element refers to the view name
     *   for rendering the HTML body, while 'text' element is for rendering the text body.
     *   For example, `['html' => 'contact-html', 'text' => 'contact-text']`.
     * @param array $viewParameters The parameters (name-value pairs)
     * that will be extracted and available in the view file.
     * @param array $layoutParameters The parameters (name-value pairs)
     * that will be extracted and available in the layout file.
     *
     * @throws Throwable If an error occurred during rendering.
     *
     * @return MessageInterface The message with the added body.
     *
     * @psalm-suppress MixedArgument
     */
    public function addToMessage(
        MessageInterface $message,
        $view,
        array $viewParameters = [],
        array $layoutParameters = []
    ): MessageInterface {
        if (is_string($view)) {
            $html = $this->renderHtml($view, $viewParameters, $layoutParameters);
            return $message->withHtmlBody($html)->withTextBody($this->generateTextBodyFromHtml($html));
        }

        if (!is_array($view) || (!isset($view['html']) && !isset($view['text']))) {
            throw new RuntimeException(
                'The "$view" parameter must be a string or array with at least one "text" or "html" key.',
            );
        }

        if (isset($view['html'])) {
            $html = $this->renderHtml($view['html'], $viewParameters, $layoutParameters);
            $message = $message->withHtmlBody($html);
        }

        if (isset($view['text'])) {
            $text = $this->renderText($view['text'], $viewParameters, $layoutParameters);
            $message = $message->withTextBody($text);
        }

        if (isset($html) && !isset($text)) {
            $message = $message->withTextBody($this->generateTextBodyFromHtml($html));
        }

        return $message;
    }

    /**
     * Renders the HTML view specified with optional parameters and layout.
     *
     * @param string $view The view name of the view file.
     * @param array $viewParameters The parameters (name-value pairs)
     * that will be extracted and available in the view file.
     * @param array $layoutParameters The parameters (name-value pairs)
     * that will be extracted and available in the layout file.
     *
     * @throws Throwable If an error occurred during rendering.
     *
     * @see View::render()
     *
     * @return string The rendering HTML result.
     */
    public function renderHtml(string $view, array $viewParameters = [], array $layoutParameters = []): string
    {
        $content = $this->view->withContext($this->template)->render($view, $viewParameters);

        if ($this->template->getHtmlLayout() === '') {
            return $content;
        }

        $layoutParameters['content'] = $content;
        return $this->view->withContext($this->template)->render($this->template->getHtmlLayout(), $layoutParameters);
    }

    /**
     * Renders the TEXT view specified with optional parameters and layout.
     *
     * @param string $view The view name of the view file.
     * @param array $viewParameters The parameters (name-value pairs)
     * that will be extracted and available in the view file.
     * @param array $layoutParameters The parameters (name-value pairs)
     * that will be extracted and available in the layout file.
     *
     * @throws Throwable If an error occurred during rendering.
     *
     * @see View::render()
     *
     * @return string The rendering TEXT result.
     */
    public function renderText(string $view, array $viewParameters = [], array $layoutParameters = []): string
    {
        $content = $this->view->withContext($this->template)->render($view, $viewParameters);

        if ($this->template->getTextLayout() === '') {
            return $content;
        }

        $layoutParameters['content'] = $content;
        return $this->view->withContext($this->template)->render($this->template->getTextLayout(), $layoutParameters);
    }

    /**
     * Returns a new instance with the specified view.
     *
     * @param View $view The view instance.
     *
     * @return self The new instance.
     */
    public function withView(View $view): self
    {
        $new = clone $this;
        $new->view = $view;
        return $new;
    }

    /**
     * Returns a new instance with the specified message body template.
     *
     * @param MessageBodyTemplate $template The message body template.
     *
     * @return self The new instance.
     */
    public function withTemplate(MessageBodyTemplate $template): self
    {
        $new = clone $this;
        $new->template = $template;
        return $new;
    }

    /**
     * Generates a TEXT body from an HTML body.
     *
     * @param string $html The HTML body.
     *
     * @return string The TEXT body.
     */
    private function generateTextBodyFromHtml(string $html): string
    {
        if (preg_match('~<body[^>]*>(.*?)</body>~is', $html, $match)) {
            $html = $match[1];
        }
        // remove style and script
        $html = preg_replace('~<((style|script))[^>]*>(.*?)</\1>~is', '', $html);
        // strip all HTML tags and decode HTML entities
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5);
        // improve whitespace
        $text = preg_replace("~^[ \t]+~m", '', trim($text));
        return preg_replace('~\R\R+~mu', "\n\n", $text);
    }
}
