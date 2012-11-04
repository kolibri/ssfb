<?php
/**
 * Konfiguration.
 * Erst lesen, dann verstehen, dann ändern.
 * Im Zweifel nachfragen: lukas.sadzik@gmail.com
 */
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


/**
 * AB HIER NICHTS MEHR ÄNDERN!!!
 * Für jede Änderung unter diesem Kommentarblock stirbt ein Einhorn.
 * Echt jetzt!
 */

require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();
$app['debug'] = true;

// twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// swift mailer
$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app['swiftmailer.options'] = $config['mailer'];

// adding templates default vars (mail address, telephon enumber)
$app['twig']->addGlobal('email', $config['email']);
$app['twig']->addGlobal('phone', $config['phone']);
$app['twig']->addGlobal('phone_short', $config['phone_short']);


/**
 * form submit
 */
$app->post('/{name}/form_submit', function($name) use($app) {
    $app['twig']->addGlobal('active', $name);
    $request = $app['request'];
    $form_vars = array(
        'nachname' => $request->get('nachname'),
        'vorname' => $request->get('vorname'),
        'firma' => $request->get('firma'),
        'addresse' => $request->get('addresse'),
        'telefon' => $request->get('telefon'),
        'email' => $request->get('email'),
        'text' => $request->get('text'),
    );
  
    $message_body =  $app['twig']->render('_contact_mail.txt.twig', $form_vars);
    $message = \Swift_Message::newInstance()
        ->setSubject('[KaYvo] Kontakt')
        ->setFrom($config['mailer']['send_to_address'])
        ->setTo($config['mailer']['send_from_address'])
        ->setBody($message_body)
    ;
    
    $message_sent = false;
    if($app['mailer']->send($message)) {
        $message_sent = true;
    }
    return $app['twig']->render('_form_submit.html.twig', array('message_sent' => $message_sent));
})->value()
->bind('form_submit');

/**
 * form partial
 */
$app->match('/contact_form', function() use($app) {
    return $app['twig']->render('_contact_form.html.twig');
})->value()
->bind('contact_form'); 

/**
 * Default Route
 */
$app->match('/{name}', function($name) use($app) {
    $app['twig']->addGlobal('active', $name);
    return $app['twig']->render($name.'.html.twig', array(
        'name' => $name,
    ));
})->value('name', 'welcome')
->bind('page'); 

$app->run();
