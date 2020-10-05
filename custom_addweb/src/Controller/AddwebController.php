<?php

namespace Drupal\custom_addweb\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AddwebController.
 */
class AddwebController extends ControllerBase {

  /**
   * Custom_addweb json.
   *
   * @param string $key
   *   Site api key.
   * @param int $nid
   *   Node id.
   *
   * @return jsonoutput
   *
   *   Json response for page node.
   */
  public function customAaddwebJson($key, $nid) {
    // Get the Site API Key from configuration.
    $site_api_key = \Drupal::config('system.site')->get('site_api_key');
    $default_content_type = \Drupal::config('system.site')->get('api_content_type');
    // Check if the API Key entered in the URL is Valid.
    if ($site_api_key === $key) {

      if (is_numeric($nid) && $nid > 0) {

        // Load the Node using the Node id from the request URL.
        $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);

        // Check if the node type is 'page'.
        if (!empty($node) && $node->getType() === $default_content_type) {
          // Build appropriate JSON response.
          $json_response = [
            'nid' => $nid,
            'type' => $node->getType(),
            'title' => $node->getTitle(),
            'body' => $node->get('body')->getValue(),
          ];
          // Respond with the json representation of the node.
          return new JsonResponse($json_response);
        }
        else {
          // When other content types.
          $response['success'] = FALSE;
          $response['error'] = 'access denied';
          $response['message'] = t('Not the desired content type (@type), please check on the Site Details.', ['@type' => $default_content_type]);
          $response['Content-Type'] = 'application/json';
          return new JsonResponse($response);
        }
      }
    }
    else {
      // Build appropriate JSON response.
      $response['success'] = FALSE;
      $response['error'] = 'access denied';
      $response['message'] = t('Site API Key Error');
      return new JsonResponse($response);
    }
  }

}
