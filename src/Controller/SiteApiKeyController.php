
<?php
namespace Drupal\site_api_key\Controller;
/**
  @file
  Contains \Drupal\site_api_key\Controller\SiteApikeyController.
 */
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
class SiteApiKeyController extends ControllerBase {

/**
 * Function to check content access and then render JSON output for page node type.
 *
 * @param $site_api_key
 *   A String passed from the 'site info' form
 *
 * @param $api_key
 *   checks if value was passed
 *
 * @param $node
 *   A String to compare node object
 *
 * @return array
 *
 * A Json Response displayed on URI  http://localhost/page_json/[apikey]FOOBAR12345/[nid]17
 *
*/
public function checkAccess($api_key, NodeInterface $node) {
  // In a namespace, for example a form class, use \Drupal::config() instead of Drupal::config().
  $site_api_key = \Drupal::config('system.site')->get('siteapikey');
  if ($api_key === $siteapikey) {
	  
    // Generate error if node id is absent or is not of type 'page.'
	if (!is_object($node)) {
      echo t('Error fetching content.');
      die();
    }
    
    // check if node is of type 'page' & then use the serializer service to serialize a Drupal data type so that normalization is run properly.
	if ($node->getType() === 'page') {
      // note: enable the serialization module which defines the serializer service.
      $serializer = \Drupal::service('serializer');
	  // convert drupal data to JSON
      $json_response = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
      // Display the JSON Response.
	  echo $json_response;
      die();
    }
    echo t('Error matching content type or Node does not exist.');
    die();
    }
    echo t('Access Denied.');
    die();
}

/**
 * {@inheritdoc}
*/
public function site_api_key_form_validate($form, \Drupal\Core\Form\FormStateInterface $form_state) {
  if (strlen($form['site_information']['siteapikey']['#value']) !== 16) {
    $form_state->setErrorByName('siteapikey', t('Not Less or Greater then 16 characters !!!'));
  } // If no errors are registered during form validation then Drupal continues on with processing the form.
}

/**
 * {@inheritdoc}
*/
public function site_api_key_form_submit($form, \Drupal\Core\Form\FormStateInterface $form_state) {
  $site_api_key = $form['site_information']['siteapikey']['#value'] ? $form['site_information']['siteapikey']['#value'] : t('No API Key yet');
    \Drupal::configFactory()->getEditable('system.site')
	->set('siteapikey', $site_api_key)
	->save();
  }
}