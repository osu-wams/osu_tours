<?php

namespace Drupal\osu_tours\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Provides a block that displays role-based tour links.
 *
 * @Block(
 *   id = "role_based_tours_block",
 *   admin_label = @Translation("Role-Based Tours Block"),
 *   category = @Translation("OSU Tours")
 * )
 */
class RoleBasedToursBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_user = \Drupal::currentUser();
    $user_roles = array_map('strtolower', $current_user->getRoles());

    // Define the tour data grouped by category.
    $tour_data = [
      'People/Users' => [
        ['title' => 'Users – People Page Tour', 'path' => '/admin/people?tour=1', 'roles' => ['manage_users', 'architect']],
        ['title' => 'Users – Add User', 'path' => '/admin/people/create?tour=1', 'roles' => ['manage_users', 'architect']],
        ['title' => 'Users – Bulk Add CAS Users Page', 'path' => '/admin/people/create/cas-bulk?tour=1', 'roles' => ['manage_users', 'architect']],
      ],
      'Content' => [
        ['title' => 'Node page', 'path' => '/?tour=1', 'roles' => ['content_authors', 'group_content_author', 'manage_content', 'architect']],
        ['title' => 'Edit Functions Tour', 'path' => '/node/add/page?tour=1', 'roles' => ['content_authors', 'group_content_author', 'manage_content', 'architect']],
        ['title' => 'Content Overview', 'path' => '/admin/content?tour=1', 'roles' => ['content_authors', 'manage_content', 'architect']],
        ['title' => 'Editoria11y', 'path' => '/admin/reports/editoria11y?tour=1', 'roles' => ['content_authors', 'group_content_author', 'manage_content', 'manage_blocks', 'architect']],
        ['title' => 'Media', 'path' => '/admin/content/media?tour=1', 'roles' => ['content_authors', 'manage_content', 'architect']],
        ['title' => 'Layout Builder', 'path' => '/node/86/layout?tour=1', 'roles' => ['content_authors', 'group_content_author', 'manage_content', 'architect']],
      ],
      'Theme' => [
        ['title' => 'Madrone Theme Settings', 'path' => '/admin/appearance/settings/madrone?tour=1', 'roles' => ['manage_site_configuration', 'architect']],
      ],
      'Menus' => [
        ['title' => 'Menus – Menu overview page', 'path' => '/admin/structure/menu?tour=1', 'roles' => ['manage_menus', 'architect']],
        ['title' => 'Menus – Edit menu', 'path' => '/admin/structure/menu/manage/main?tour=1', 'roles' => ['manage_menus', 'architect']],
        ['title' => 'Menus – Edit link', 'path' => '/admin/structure/menu/item/91/edit?tour=1', 'roles' => ['manage_menus', 'architect']],
      ],
      'Structure' => [
        ['title' => 'Block Layout Tour', 'path' => '/admin/structure/block?tour=1', 'roles' => ['manage_blocks', 'architect']],
        ['title' => 'Content Type Creation', 'path' => '/admin/structure/types/add?tour=1', 'roles' => ['manage_content', 'architect']],
        ['title' => 'Group Overview Page', 'path' => '/admin/group?tour=1', 'roles' => ['architect']],
      ],
      'Views' => [
        ['title' => 'View edit page', 'path' => '/admin/structure/views/view/osu_stories?tour=1', 'roles' => ['architect']],
      ],
      'Aliases & Redirects' => [
        ['title' => 'Redirects – Overview Page', 'path' => '/admin/config/search/redirect?tour=1', 'roles' => ['content_authors', 'manage_content', 'manage_site_configuration', 'architect']],
        ['title' => 'Redirects – Create', 'path' => '/admin/config/search/redirect/add?tour=1', 'roles' => ['content_authors', 'manage_content', 'manage_site_configuration', 'architect']],
        ['title' => 'Aliases – Overview page', 'path' => '/admin/config/search/path?tour=1', 'roles' => ['content_authors', 'group_content_author', 'manage_content', 'manage_site_configuration', 'architect']],
      ],
      'Tours' => [
        ['title' => 'Tours', 'path' => '/admin/config/user-interface/tour?tour=1', 'roles' => ['architect']],
        ['title' => 'Edit tour', 'path' => '/admin/config/user-interface/tour/manage/add_user?tour=1', 'roles' => ['architect']],
        ['title' => 'Edit tip', 'path' => '/admin/config/user-interface/tour/manage/add_user/tip/edit/add_user_general?tour=1', 'roles' => ['architect']],
      ],
      'Security' => [
        ['title' => 'Honeypot', 'path' => '/admin/config/content/honeypot?tour=1', 'roles' => ['manage_site_configuration', 'architect']],
      ],
    ];

    $grouped_links = [];

    foreach ($tour_data as $category => $tours) {
      foreach ($tours as $tour) {
        if (array_intersect($user_roles, array_map('strtolower', $tour['roles']))) {
          $grouped_links[$category][] = [
            'title' => $tour['title'],
            'url' => \Drupal\Core\Url::fromUserInput($tour['path'])->toString(),
          ];
        }
      }
    }

    return [
      '#theme' => 'role_based_tours_block',
      '#links' => $grouped_links,
      '#cache' => [
        'contexts' => ['user.roles'],
      ],
    ];
  }

}
