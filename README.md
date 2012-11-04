#Documentation

## Usage

### Include contact form
    {{ render(path('contact_form')) }}
Will include the contact form.
It's content is rendered via web/views/_contact_mail.txt.twig and send to the 
email address set for '$config['contact_sen_to']'.
After sending a thank you page will be displayed.

Attention: There is no validation (yet)!


## Used software
Silex as framework (http://silex.sensiolabs.org)
Twig as template engine (http://twig.sensiolabs.org)
Swiftmailer for sending emails (http://swiftmailer.org)
jQuery lightbox as picture viewer (http://leandrovieira.com/projects/jquery/lightbox)
Twitter Bootstrap as CSS framework (http://twitter.github.com/bootstrap)




$config  = array();
// Template Variablen
$config['email'] = 'info@kayvo.de'; // Emailaddresse, fürs Impressum und andere Stellen.
$config['phone'] = '0176/66 72 82 49'; // Telefonnummer, wie sie angezeigt werden soll (z.B. 01234/ 657 89 0)
$config['phone_short'] = '+4917666728249'; // Telefonnummer im internationalem Format ohne Sonderzeichen (z.B. +491234567890)

// Kontaktformular Addressen
$config['send_to_address'] = ''; // An welche Adresse sollen die Kontaktanfragen gesendet werden
$config['send_from_address'] = ''; // Von welcher Adresse sollen die Kontaktanfragen gesendet werden

// Mailer konfiguration
$config['mailer'] = array();
$config['mailer']['host'] = '';          // Mailserver Name. I.d.R der Part hinter dem @ (torben@tester.dev => tester.dev)
$config['mailer']['port'] = '';            // Mail Port. Muss i.d.R. nicht geändert werden
$config['mailer']['username'] = '';  // Benutzername für das Emailkonto. 
$config['mailer']['password'] = '';  // Passwort für das Emailkonto
#$config['mailer']['encryption'] = 'ssl';      // Verschlüsselung. Muss i.d.R. nicht geändert werden
#$config['mailer']['auth_mode'] = null;        // Autentifizierungs Modus. Muss i.d.R nicht geändert werden
