<?php
/**
 * Mailer service
 *
 * @package   src/Service
 * @version   0.0.1
 * @author    Adrien Colonna
 * @copyright no copyrights
 */

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

/**
 * Class MailerService
 *
 * @package App\Service
 */
class MailerService
{

    /**
     * Mailer
     *
     * @var MailerInterface
     */
    private $mailer;

    /**
     * Twig
     *
     * @var Environment
     */
    private $twig;

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;


    /**
     * MailerService constructor.
     *
     * @param MailerInterface $mailer Mailer component.
     * @param Environment     $twig   Twig.
     * @param LoggerInterface $logger Logger.
     */
    public function __construct(MailerInterface $mailer, Environment $twig, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->twig   = $twig;
        $this->logger = $logger;

    }//end __construct()


    /**
     * Send msg
     *
     * @param string $subject          Subject of this message.
     * @param string $from             Sender of this message.
     * @param string $to               Recipient of this message.
     * @param string $twigTemplatePath Template of this message.
     * @param array  $templateVars     Vars template of this message.
     *
     * @return null Null.
     */
    public function sendMessage(string $subject, string $from, string $to, string $twigTemplatePath, array $templateVars=[])
    {
        try {
            $message = (new TemplatedEmail())->subject($subject)->from($from)->to($to)->htmlTemplate($twigTemplatePath)->context($templateVars);
            return $this->mailer->send($message);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Exception when calling Mailer: %s', $e->getMessage()), []);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error(sprintf('Exception when calling send Mailer: %s', $e->getMessage()), []);
        }

        return null;

    }//end sendMessage()


}//end class
