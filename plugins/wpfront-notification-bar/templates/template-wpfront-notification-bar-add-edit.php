<?php
/*
  WPFront Notification Bar Plugin
  Copyright (C) 2013, WPFront.com
  Website: wpfront.com
  Contact: syam@wpfront.com

  WPFront Notification Bar Plugin is distributed under the GNU General Public License, Version 3,
  June 2007. Copyright (C) 2007 Free Software Foundation, Inc., 51 Franklin
  St, Fifth Floor, Boston, MA 02110, USA

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
  ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Template for WPFront Notification Bar Options
 *
 * @author Syam Mohan <syam@wpfront.com>
 * @copyright 2013 WPFront.com
 */

namespace WPFront\Notification_Bar;

require_once(dirname(__DIR__) . "/classes/class-wpfront-notification-bar.php");

if (!class_exists('\WPFront\Notification_Bar\WPFront_Notification_Bar_Add_Edit_View')) {

    class WPFront_Notification_Bar_Add_Edit_View { //TODO: add upgrade link

        /**
         *
         * @var WPFront_Notification_Bar_Controller
         */

        protected $controller;
        protected $options;

        public function __construct($controller) {
            $this->controller = $controller;
            $this->options = $this->controller->get_options();
            if (!empty($_POST['submit2']) || !empty($_POST['submit'])) {
                $this->options->read_from_post();
            }
        }

        public function view() {
            ?> 
            <div class="wrap notification-bar-add-edit">
                <?php $this->title(); ?>
                <div id="wpfront-notification-bar-options" class="inside">
                    <?php
                    $action = apply_filters('wpfront_notification_bar_options_page_action', 'options.php');
                    ?>
                    <form id="wpfront-notification-bar-options-form" method="post" action="<?php echo $action; ?>">
                        <?php
                        settings_fields(WPFront_Notification_Bar::OPTIONS_GROUP_NAME);
                        do_settings_sections('wpfront-notification-bar');

                        if ((isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') || (isset($_GET['updated']) && $_GET['updated'] == 'true')) { //TODO: w3tc test
                            ?>
                            <div class="updated">
                                <p>
                                    <strong><?php echo __('If you have a caching plugin, clear the cache for the new settings to take effect.', 'wpfront-notification-bar'); ?></strong>
                                </p>
                            </div>
                            <?php
                        }
                        ?>
                        <?php $this->create_meta_boxes(); ?>
                        <div id="poststuff">
                            <div id="post-body" class="metabox-holder columns-2" style="display:flow-root">
                                <div id="post-body-content" style="position:relative">
                                    <?php do_meta_boxes($this->controller->get_menu_slug(), 'normal', null); ?>
                                </div>
                                <div id="postbox-container-1" class="postbox-container" style="position: sticky; top: 40px;">
                                    <?php do_meta_boxes($this->controller->get_menu_slug(), 'side', null); ?>
                                </div>
                            </div>
                        </div>                     
                        <?php $this->script(); ?>
                        <input type="hidden" name="<?php echo $this->options->last_saved_name(); ?>" value="<?php echo time(); ?>" />
                        <?php $this->nonce_field(); ?>
                        <?php submit_button(null, 'primary', 'submit2', false); ?>
                    </form>
                </div>
            </div>
            <?php
        }

        protected function title() {
            ?>
            <h2>
                <?php echo __('WPFront Notification Bar Settings', 'wpfront-notification-bar'); ?>
            </h2>
            <?php
        }

        public function name_field() {
            
        }

        public function nonce_field() {
            
        }

        protected function get_meta_box_groups() {
            return [
                (object) [
                    'group_name' => 'Display_Settings',
                    'title' => __('Display', 'wpfront-notification-bar'),
                    'render' => 'postbox_notification_bar_display_settings'
                ],
                (object) [
                    'group_name' => 'Content_Settings',
                    'title' => __('Content', 'wpfront-notification-bar'),
                    'render' => 'postbox_notification_bar_content_settings'
                ],
                (object) [
                    'group_name' => 'Filter_Settings',
                    'title' => __('Filter', 'wpfront-notification-bar'),
                    'render' => 'postbox_notification_bar_filter_settings'
                ],
                (object) [
                    'group_name' => 'Color_Settings',
                    'title' => __('Color', 'wpfront-notification-bar'),
                    'render' => 'postbox_notification_bar_color_settings'
                ],
                (object) [
                    'group_name' => 'CSS_Settings',
                    'title' => __('CSS', 'wpfront-notification-bar'),
                    'render' => 'postbox_notification_bar_css_settings'
                ]
            ];
        }

        protected function create_meta_boxes() {
            $groups = $this->get_meta_box_groups();

            foreach ($groups as $group) {
                add_meta_box("postbox-{$group->group_name}", $group->title, array($this, $group->render), $this->controller->get_menu_slug(), 'normal', 'default', $group);
            }
            add_meta_box("postbox-side-1", __('Action', 'wpfront-notification-bar'), array($this, 'action_buttons'), $this->controller->get_menu_slug(), 'side', 'default', $group);
            $this->upgrade_to_pro_metabox($group);

            wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false);
            wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false);
        }

        public function upgrade_to_pro_metabox($group) {
            add_meta_box("postbox-side-2", __('Upgrade to Pro', 'wpfront-notification-bar'), array($this, 'free_to_pro'), $this->controller->get_menu_slug(), 'side', 'default', $group);
        }

        public function action_buttons() {
            submit_button();
        }

        public function free_to_pro() {
            ?>
            <div class="free-to-pro">
                <p>
                    <?php esc_html_e('PRO version offers the following features.', 'wpfront-notification-bar'); ?>
                </p>
                <ul>
                    <li><?php echo __('Create Multiple Bars', 'wpfront-notification-bar'); ?></li>     
                    <li><?php echo __('Advanced Editor', 'wpfront-notification-bar'); ?></li>
                    <li><?php echo __('Recurring Schedule', 'wpfront-notification-bar'); ?></li>
                    <li><?php echo __('Custom Capabilities', 'wpfront-notification-bar'); ?></li>
                    <li><?php echo __('Premium Support', 'wpfront-notification-bar'); ?></li>
                </ul>   
                <?php $this->discount_code(); ?>
                <p class="upgrade-button">
                    <a class="button button-primary" href="https://wpfront.com/notification-bar-pro/" target="_blank" rel="noopener noreferrer"><?php echo __('Upgrade', 'wpfront-notification-bar'); ?></a>
                </p>
            </div>
            <?php
        }

        private function discount_code() {
            $now = time();
            $until = strtotime("2022-01-31");
            if ($now < $until) {
                ?>
                <p class="discount-tip">
                    <?php esc_html_e('Use the following code to purchase the PRO version on a discount:', 'wpfront-notification-bar'); ?>
                </p>
                <p class="discount-code">
                    NBJANEP
                </p>
                <?php
            }
        }

        public function postbox_notification_bar_display_settings() {
            ?>
            <table class="form-table">
                <?php $this->name_field(); ?>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->enabled_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->enabled_name(); ?>" <?php echo $this->options->enabled() ? 'checked' : ''; ?> />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->preview_mode_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->preview_mode_name(); ?>" <?php echo $this->options->preview_mode() ? 'checked' : ''; ?> />
                        <?php
                        if ($this->options->preview_mode()) {
                            $url = $this->controller->get_preview_url();
                            ?>
                            <span class="description"><a target="_blank" rel="noopener" href="<?php echo $url; ?>"><?php echo $url; ?></a></span>
                            <?php
                        } else {
                            $description = __('You can test the notification bar without enabling it.', 'wpfront-notification-bar');
                            $this->echo_help_tooltip($description);
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->debug_mode_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->debug_mode_name(); ?>" <?php echo $this->options->debug_mode() ? 'checked' : ''; ?> />
                        <?php
                        $description = __('Enable to see logs in browser.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                        <span class="description">
                            <a target="_blank" rel="noopener" href="https://wpfront.com/wordpress-plugins/notification-bar-plugin/wpfront-notification-bar-troubleshooting/"><?php echo __('How to?', 'wpfront-notification-bar'); ?></a>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->position_label(); ?>
                    </th>
                    <td>
                        <select name="<?php echo $this->options->position_name(); ?>">
                            <option value="1" <?php echo $this->options->position() == '1' ? 'selected' : ''; ?>><?php echo __('Top', 'wpfront-notification-bar'); ?></option>
                            <option value="2" <?php echo $this->options->position() == '2' ? 'selected' : ''; ?>><?php echo __('Bottom', 'wpfront-notification-bar'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->fixed_position_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->fixed_position_name(); ?>" <?php echo $this->options->fixed_position() ? 'checked' : ''; ?> />&#160;
                        <?php
                        $description = __('Sticky Bar, bar will stay at position regardless of scrolling.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->theme_sticky_selector_label(); ?>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="<?php echo $this->options->theme_sticky_selector_name(); ?>" value="<?php echo esc_attr($this->options->theme_sticky_selector()); ?>" />
                        <?php
                        $description = __('If your page already has a sticky bar enter the element selector here. For example, for Avada theme it will be "<b>.fusion-is-sticky .fusion-header</b>".', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->display_scroll_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->display_scroll_name(); ?>" <?php echo $this->options->display_scroll() ? 'checked' : ''; ?> />&#160;

                        <?php
                        $description = __('Displays the bar on window scroll.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>                       
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->display_scroll_offset_label(); ?>
                    </th>
                    <td>
                        <input class="seconds" name="<?php echo $this->options->display_scroll_offset_name(); ?>" value="<?php echo esc_attr($this->options->display_scroll_offset()); ?>" />&#160;<?php echo __('px', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Number of pixels to be scrolled before the bar appears.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->height_label(); ?>
                    </th>
                    <td>
                        <input class="seconds" name="<?php echo $this->options->height_name(); ?>" value="<?php echo esc_attr($this->options->height()); ?>" />&#160;<?php echo __('px', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Set 0px to auto fit contents.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->position_offset_label(); ?>
                    </th>
                    <td>
                        <input class="seconds" name="<?php echo $this->options->position_offset_name(); ?>" value="<?php echo esc_attr($this->options->position_offset()); ?>" />&#160;<?php echo __('px', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('(Top bar only) If you find the bar overlapping, try increasing this value. (eg. WordPress 3.8 Twenty Fourteen theme, set 48px)', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->display_after_label(); ?>
                    </th>
                    <td>
                        <input class="seconds" name="<?php echo $this->options->display_after_name(); ?>" value="<?php echo esc_attr($this->options->display_after()); ?>" />&#160;
                        <?php echo __('second(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Set 0 second(s) to display immediately. Does not work in "<b>Display on Scroll</b>" mode.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->animate_delay_label(); ?>
                    </th>
                    <td>
                        <input class="seconds" name="<?php echo $this->options->animate_delay_name(); ?>" value="<?php echo esc_attr($this->options->animate_delay()); ?>" />&#160;<?php echo __('second(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Set 0 second(s) for no animation.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->close_button_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->close_button_name(); ?>" <?php echo $this->options->close_button() ? 'checked' : ''; ?> />&#160;
                        <?php
                        $description = __('Displays a close button at the top right corner of the bar.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->auto_close_after_label(); ?>
                    </th>
                    <td>
                        <input class="seconds" name="<?php echo $this->options->auto_close_after_name(); ?>" value="<?php echo esc_attr($this->options->auto_close_after()); ?>" />&#160;<?php echo __('second(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Set 0 second(s) to disable auto close. Do not work in "<b>Display on Scroll</b>" mode.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->display_shadow_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->display_shadow_name(); ?>" <?php echo $this->options->display_shadow() ? 'checked' : ''; ?> />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->display_open_button_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->display_open_button_name(); ?>" <?php echo $this->options->display_open_button() ? 'checked' : ''; ?> />&#160;
                        <?php
                        $description = __('A reopen button will be displayed after the bar is closed.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->reopen_button_image_url_label(); ?>
                    </th>
                    <td>
                        <input id="reopen-button-image-url" class="URL" name="<?php echo $this->options->reopen_button_image_url_name(); ?>" value="<?php echo esc_attr($this->options->reopen_button_image_url()); ?>"/>
                        <input type="button" id="media-library-button" class="button" value="<?php echo __('Media Library', 'wpfront-notification-bar'); ?>" />
                        <?php
                        $description = __('Set empty value to use default images.');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->reopen_button_offset_label(); ?>
                    </th>
                    <td>
                        <input class="seconds" name="<?php echo $this->options->reopen_button_offset_name(); ?>" value="<?php echo esc_attr($this->options->reopen_button_offset()); ?>" />&#160;<?php echo __('px', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Moves the button more to the left.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->keep_closed_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->keep_closed_name(); ?>" <?php echo $this->options->keep_closed() ? 'checked' : ''; ?> />&#160;
                        <?php
                        $description = __('Once closed, bar will display closed on other pages.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->keep_closed_for_label(); ?>
                    </th>
                    <td>
                        <input class="seconds" name="<?php echo $this->options->keep_closed_for_name(); ?>" value="<?php echo esc_attr($this->options->keep_closed_for()); ?>" />&#160;<?php echo __('day(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Bar will be kept closed for the number of days specified from last closed date.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->keep_closed_cookie_name_label(); ?>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="<?php echo $this->options->keep_closed_cookie_name_name(); ?>" value="<?php echo esc_attr($this->options->keep_closed_cookie_name()); ?>" />
                        <?php
                        $description = __('Cookie name used to mark keep closed days. Changing this value will allow you to bypass "<b>Keep Closed For</b>" days and show the notification again.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->set_max_views_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->set_max_views_name(); ?>" <?php echo $this->options->set_max_views() ? 'checked' : ''; ?> />&#160;
                        <?php
                        $description = __('Bar will be hidden after a certain number of views.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->max_views_label(); ?>
                    </th>
                    <td>
                        <input class="seconds" name="<?php echo $this->options->max_views_name(); ?>" value="<?php echo esc_attr($this->options->max_views()); ?>" />&#160;<?php echo __('time(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Maximum number of views.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->max_views_for_label(); ?>
                    </th>
                    <td>
                        <input class="seconds" name="<?php echo $this->options->max_views_for_name(); ?>" value="<?php echo esc_attr($this->options->max_views_for()); ?>" />&#160;<?php echo __('day(s)', 'wpfront-notification-bar'); ?>&#160;
                        <?php
                        $description = __('Bar will be kept closed for the number of days specified. Zero means current session.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->max_views_cookie_name_label(); ?>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="<?php echo $this->options->max_views_cookie_name_name(); ?>" value="<?php echo esc_attr($this->options->max_views_cookie_name()); ?>" />
                        <?php
                        $description = __('Cookie name used to store view count.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->hide_small_device_label(); ?>
                    </th>
                    <td>
                        <div>
                            <label><input type="radio" class="hide_small_device" name="<?php echo $this->options->hide_small_device_name(); ?>" value="all" <?php echo $this->options->hide_small_device() == 'all' ? 'checked' : ''; ?> /> <?php echo __('All Devices', 'wpfront-notification-bar'); ?></label>
                            <br />
                            <label><input type="radio" class="hide_small_device" name="<?php echo $this->options->hide_small_device_name(); ?>" value="small" <?php echo $this->options->hide_small_device() == 'small' ? 'checked' : ''; ?> /> <?php echo __('Small Devices', 'wpfront-notification-bar'); ?></label>
                            <br />
                            <label><input type="radio" class="hide_small_device" name="<?php echo $this->options->hide_small_device_name(); ?>" value="large" <?php echo $this->options->hide_small_device() == 'large' ? 'checked' : ''; ?> /> <?php echo __('Except Small Devices ', 'wpfront-notification-bar'); ?></label>
                        </div>

                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->small_device_width_label(); ?>
                        <?php $value = $this->options->small_device_width(); ?>
                    </th>
                    <td>
                        <input class="pixels" name="<?php echo $this->options->small_device_width_name(); ?>" value="<?php echo esc_attr($this->options->small_device_width()); ?>" />px 
                        <?php
                        $description = __('Devices with width greater than the specified width will be considered as large devices.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->hide_small_window_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->hide_small_window_name(); ?>" <?php echo $this->options->hide_small_window() ? "checked" : ""; ?> />
                        <?php
                        $description = __('Notification bar will be hidden on broswer window when the width matches.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->small_window_width_label(); ?>
                    </th>
                    <td>
                        <input class="pixels" name="<?php echo $this->options->small_window_width_name(); ?>" value="<?php echo esc_attr($this->options->small_window_width()); ?>" />px 
                        <?php
                        $description = __('Notification bar will be hidden on browser window with lesser or equal width.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->attach_on_shutdown_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->attach_on_shutdown_name(); ?>" <?php echo $this->options->attach_on_shutdown() ? 'checked' : ''; ?> />
                        <?php
                        $description = __('Enable as a last resort if the notification bar is not working. This could create compatibility issues.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
            </table>
            <?php
        }

        public function postbox_notification_bar_content_settings() {
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php echo $this->options->set_full_width_message_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->set_full_width_message_name(); ?>" <?php echo $this->options->set_full_width_message() ? 'checked' : ''; ?> />
                    </td>
                </tr>
                <?php $this->message_field(); ?>              
                <tr>
                    <th scope="row">
                        <?php echo __('Message Text Preview', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <textarea rows="5" cols="75" readonly="true"><?php echo esc_textarea($this->controller->get_message_text()); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->message_process_shortcode_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->message_process_shortcode_name(); ?>" <?php echo $this->options->message_process_shortcode() ? 'checked' : ''; ?> />&#160;
                        <?php
                        $description = __('Processes shortcodes in message text.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->display_button_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->display_button_name(); ?>" <?php echo $this->options->display_button() ? 'checked' : ''; ?> />&#160;
                        <?php
                        $description = __('Displays a button next to the message.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->button_text_label(); ?>
                    </th>
                    <td>
                        <input name="<?php echo $this->options->button_text_name(); ?>" value="<?php echo esc_attr($this->options->button_text()); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Button Text Preview', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <input readonly="true" value="<?php echo esc_attr($this->controller->get_button_text()); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->button_action_label(); ?>
                    </th>
                    <td>
                        <label>
                            <input type="radio" name="<?php echo $this->options->button_action_name(); ?>" value="1" <?php echo $this->options->button_action() == 1 ? 'checked' : ''; ?> />
                            <span><?php echo $this->options->button_action_url_label(); ?></span>
                        </label>
                        <input class="URL" name="<?php echo $this->options->button_action_url_name(); ?>" value="<?php echo esc_attr($this->options->button_action_url()); ?>" />
                        <br />
                        <label>
                            <input type="checkbox" name="<?php echo $this->options->button_action_new_tab_name(); ?>" <?php echo $this->options->button_action_new_tab() ? 'checked' : ''; ?> />
                            <span><?php echo $this->options->button_action_new_tab_label() . '.'; ?></span>
                        </label>
                        <br />
                        <label>
                            <input type="checkbox" name="<?php echo $this->options->button_action_url_nofollow_name(); ?>" <?php echo $this->options->button_action_url_nofollow() ? 'checked' : ''; ?> />
                            <span><?php echo $this->options->button_action_url_nofollow_label() . '.'; ?></span>
                        </label>
                        <?php
                        $description = __('rel="<b>nofollow</b>"', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                        <br />
                        <label>
                            <input type="checkbox" name="<?php echo $this->options->button_action_url_noreferrer_name(); ?>" <?php echo $this->options->button_action_url_noreferrer() ? 'checked' : ''; ?> />
                            <span><?php echo $this->options->button_action_url_noreferrer_label() . '.'; ?></span>
                        </label>
                        <?php
                        $description = __('rel="<b>noreferrer</b>"', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                        <br />
                        <label>
                            <input id="chk_button_action_url_noopener" type="checkbox" <?php echo $this->options->button_action_url_noopener() ? 'checked' : ''; ?> />
                            <input type="hidden" id="txt_button_action_url_noopener" name="<?php echo $this->options->button_action_url_noopener_name(); ?>" value="<?php echo $this->options->button_action_url_noopener() ? '1' : '0'; ?>" />
                            <span><?php echo $this->options->button_action_url_noopener_label() . '.'; ?></span>
                        </label>
                        <?php
                        $description = __('rel="<b>noopener</b>", used when URL opens in new tab/window. Recommended value is "<b>on</b>", unless it affects your functionality.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                        <br />
                        <label>
                            <input type="radio" name="<?php echo $this->options->button_action_name(); ?>" value="2" <?php echo $this->options->button_action() == 2 ? 'checked' : ''; ?> />
                            <span><?php echo $this->options->button_action_javascript_label(); ?></span>
                        </label>
                        <br />
                        <textarea rows="5" cols="75" name="<?php echo $this->options->button_action_javascript_name(); ?>"><?php echo esc_textarea($this->options->button_action_javascript()); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->button_action_close_bar_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->button_action_close_bar_name(); ?>" <?php echo $this->options->button_action_close_bar() ? 'checked' : ''; ?> />
                    </td>
                </tr>
            </table>
            <?php
        }

        public function postbox_notification_bar_filter_settings() {
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php echo $this->options->filter_date_type_label(); ?>
                    </th>
                    <td>
                        <div>
                            <label><input id="date-none" type="radio" class="date-type" name="<?php echo $this->options->filter_date_type_name(); ?>" value="none" <?php echo $this->options->filter_date_type() == 'none' ? 'checked' : ''; ?> /> <?php echo __('None', 'wpfront-notification-bar'); ?></label>
                            <br />
                            <label><input id="start-end-date" type="radio" class="date-type" name="<?php echo $this->options->filter_date_type_name(); ?>" value="start_end" <?php echo $this->options->filter_date_type() == 'start_end' ? 'checked' : ''; ?> /> <?php echo __('Start and End Date', 'wpfront-notification-bar'); ?></label>
                            <br />
                            <label><input id="schedule-date" type="radio" class="date-type" name="<?php echo $this->options->filter_date_type_name(); ?>" value="schedule" <?php echo $this->options->filter_date_type() == 'schedule' ? 'checked' : ''; ?> /> <?php echo __('Recurring Schedule', 'wpfront-notification-bar'); ?></label>                          
                        </div>
                    </td>
                </tr>
                <tr class="start-end-date">
                    <th scope="row">
                        <?php echo $this->options->start_date_label(); ?>
                    </th>
                    <td>
                        <input class="date" name="<?php echo $this->options->start_date_name(); ?>" value="<?php echo esc_attr($this->options->start_date() == NULL ? '' : date('Y-m-d', $this->options->start_date())); ?>" />
                        <input class="time" name="<?php echo $this->options->start_time_name(); ?>" value="<?php echo esc_attr($this->options->start_time() == NULL ? '' : date('h:i a', $this->options->start_time())); ?>" />
                        &#160;
                        <?php
                        $description = __('YYYY-MM-DD hh:mm ap', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr class="start-end-date">
                    <th scope="row">
                        <?php echo $this->options->end_date_label(); ?>
                    </th>
                    <td>
                        <input class="date" name="<?php echo $this->options->end_date_name(); ?>" value="<?php echo esc_attr($this->options->end_date() == NULL ? '' : date('Y-m-d', $this->options->end_date())); ?>" />
                        <input class="time" name="<?php echo $this->options->end_time_name(); ?>" value="<?php echo esc_attr($this->options->end_time() == NULL ? '' : date('h:i a', $this->options->end_time())); ?>" />
                        &#160;
                        <?php
                        $description = __('YYYY-MM-DD hh:mm ap', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <?php
                $this->scheduled_date();
                ?>                      
                <tr>
                    <th scope="row">
                        <?php echo $this->options->display_pages_label(); ?>
                    </th>
                    <td>
                        <label>
                            <input type="radio" name="<?php echo $this->options->display_pages_name(); ?>" value="1" <?php echo $this->options->display_pages() == 1 ? 'checked' : ''; ?> />
                            <span><?php echo __('All pages.', 'wpfront-notification-bar'); ?></span>
                        </label>
                        <br />
                        <label>
                            <input type="radio" name="<?php echo $this->options->display_pages_name(); ?>" value="2" <?php echo $this->options->display_pages() == 2 ? 'checked' : ''; ?> />
                            <span><?php echo __('Only in landing page.', 'wpfront-notification-bar'); ?></span>&#160;
                            <?php
                            $description = __('The first page they visit on your website.', 'wpfront-notification-bar');
                            $this->echo_help_tooltip($description);
                            ?>
                        </label>
                        <br />
                        <label>
                            <input type="radio" name="<?php echo $this->options->display_pages_name(); ?>" value="3" <?php echo $this->options->display_pages() == 3 ? 'checked' : ''; ?> />
                            <span><?php echo __('Include in following pages.', 'wpfront-notification-bar'); ?></span>
                            <?php
                            $description = __('Use the textbox below to specify the post IDs as a comma separated list.', 'wpfront-notification-bar');
                            $this->echo_help_tooltip($description);
                            ?>
                        </label>
                        <br />
                        <input class="post-id-list" name="<?php echo $this->options->include_pages_name(); ?>" value="<?php echo esc_attr($this->options->include_pages()); ?>" />
                        <div class="pages-selection">
                            <?php
                            $objects = $this->controller->get_filter_objects();
                            foreach ($objects as $key => $value) {
                                ?>
                                <div class="page-div">
                                    <label>
                                        <input type="checkbox" value="<?php echo $key; ?>" <?php echo $this->controller->filter_pages_contains($this->options->include_pages(), $key) === FALSE ? '' : 'checked'; ?> />
                                        <?php echo $value; ?>
                                    </label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <label>
                            <input type="radio" name="<?php echo $this->options->display_pages_name(); ?>" value="4" <?php echo $this->options->display_pages() == 4 ? 'checked' : ''; ?> />
                            <span><?php echo __('Exclude in following pages.', 'wpfront-notification-bar'); ?></span>
                            <?php
                            $description = __('Use the textbox below to specify the post IDs as a comma separated list.', 'wpfront-notification-bar');
                            $this->echo_help_tooltip($description);
                            ?>
                        </label>
                        <br />
                        <input class="post-id-list" name="<?php echo $this->options->exclude_pages_name(); ?>" value="<?php echo esc_attr($this->options->exclude_pages()); ?>" />
                        <div class="pages-selection">
                            <?php
                            $objects = $this->controller->get_filter_objects();
                            foreach ($objects as $key => $value) {
                                ?>
                                <div class="page-div">
                                    <label>
                                        <input type="checkbox" value="<?php echo $key; ?>" <?php echo $this->controller->filter_pages_contains($this->options->exclude_pages(), $key) === FALSE ? '' : 'checked'; ?> />
                                        <?php echo $value; ?>
                                    </label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                        $description = __('Will display only top 50 posts and 50 pages to reduce load. Use the PostIDs textbox to apply this setting on other Posts, Pages, CPTs and Taxonomies. Taxonomy terms start with a "t".', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->landingpage_cookie_name_label(); ?>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="<?php echo $this->options->landingpage_cookie_name_name(); ?>" value="<?php echo esc_attr($this->options->landingpage_cookie_name()); ?>" />
                        <?php
                        $description = __('Cookie name used to mark landing page. Useful when you have multiple WordPress installs under same domain.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->display_roles_label(); ?>
                    </th>
                    <td>
                        <label>
                            <input type="radio" name="<?php echo $this->options->display_roles_name(); ?>" value="1" <?php echo $this->options->display_roles() == 1 ? 'checked' : ''; ?> />
                            <span><?php echo __('All users.', 'wpfront-notification-bar'); ?></span>
                        </label>
                        <br />
                        <label>
                            <input type="radio" name="<?php echo $this->options->display_roles_name(); ?>" value="2" <?php echo $this->options->display_roles() == 2 ? 'checked' : ''; ?> />
                            <span><?php echo __('All logged in users.', 'wpfront-notification-bar'); ?></span>
                        </label>
                        <br />
                        <label>
                            <input type="radio" name="<?php echo $this->options->display_roles_name(); ?>" value="3" <?php echo $this->options->display_roles() == 3 ? 'checked' : ''; ?> />
                            <span><?php echo __('Guest users.', 'wpfront-notification-bar'); ?></span>
                            <?php
                            $description = __('Non-logged in users', 'wpfront-notification-bar');
                            $this->echo_help_tooltip($description);
                            ?>
                        </label>
                        <br />
                        <label>
                            <input type="radio" name="<?php echo $this->options->display_roles_name(); ?>" value="4" <?php echo $this->options->display_roles() == 4 ? 'checked' : ''; ?> />
                            <span><?php echo __('For following user roles', 'wpfront-notification-bar'); ?></span>&nbsp;<span>[<a target="_blank" rel="noopener" href="https://wpfront.com/nbtoure"><?php echo __('Manage Roles', 'wpfront-notification-bar'); ?>]</a></span>
                        </label>
                        <br />
                        <div class="roles-selection">
                            <input type="hidden" name="<?php echo $this->options->include_roles_name(); ?>" value="<?php echo htmlentities(json_encode($this->options->include_roles())); ?>" />
                            <?php
                            foreach ($this->controller->get_role_objects() as $key => $value) {
                                ?>
                                <div class="role-div">
                                    <label>
                                        <input type="checkbox" value="<?php echo $key; ?>" <?php echo in_array($key, $this->options->include_roles()) === FALSE ? '' : 'checked'; ?> />
                                        <?php echo $value; ?>
                                    </label>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="role-div">
                                <label>
                                    <input type="checkbox" value="<?php echo WPFront_Notification_Bar::ROLE_NOROLE; ?>" <?php echo in_array(WPFront_Notification_Bar::ROLE_NOROLE, $this->options->include_roles()) === FALSE ? '' : 'checked'; ?> />
                                    <?php echo __('[No Role]', 'wpfront-notification-bar'); ?>
                                </label>
                            </div>
                            <div class="role-div">
                                <label>
                                    <input type="checkbox" value="<?php echo WPFront_Notification_Bar::ROLE_GUEST; ?>" <?php echo in_array(WPFront_Notification_Bar::ROLE_GUEST, $this->options->include_roles()) === FALSE ? '' : 'checked'; ?> />
                                    <?php echo __('[Guest]', 'wpfront-notification-bar'); ?>
                                </label>
                            </div>
                        </div>
                        <label>
                            <input type="checkbox" name="<?php echo $this->options->wp_emember_integration_name(); ?>" <?php echo $this->options->wp_emember_integration() ? 'checked' : ''; ?> />
                            <span><?php echo __('Enable WP eMember integration.', 'wpfront-notification-bar'); ?></span>
                        </label>
                    </td>
                </tr>
            </table>
            <?php
        }

        public function postbox_notification_bar_color_settings() {
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php echo __('Bar Color', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <div class="color-selector-div">
                            <div class="color-selector" color="<?php echo $this->options->bar_from_color(); ?>"></div>
                            <input type="text" class="color-value" name="<?php echo $this->options->bar_from_color_name(); ?>" value="<?php echo esc_attr($this->options->bar_from_color()); ?>" />
                        </div>
                        <div class="color-selector-div">
                            <div class="color-selector" color="<?php echo $this->options->bar_to_color(); ?>"></div>
                            <input type="text" class="color-value" name="<?php echo $this->options->bar_to_color_name(); ?>" value="<?php echo esc_attr($this->options->bar_to_color()); ?>" />
                        </div>
                        <?php
                        $description = __('Select two different colors to create a gradient.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->message_color_label(); ?>
                    </th>
                    <td>
                        <div class="color-selector" color="<?php echo $this->options->message_color(); ?>"></div>
                        <input type="text" class="color-value" name="<?php echo $this->options->message_color_name(); ?>" value="<?php echo esc_attr($this->options->message_color()); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo __('Button Color', 'wpfront-notification-bar'); ?>
                    </th>
                    <td>
                        <div class="color-selector-div">
                            <div class="color-selector" color="<?php echo $this->options->button_from_color(); ?>"></div>
                            <input type="text" class="color-value" name="<?php echo $this->options->button_from_color_name(); ?>" value="<?php echo esc_attr($this->options->button_from_color()); ?>" />
                        </div>
                        <div class="color-selector-div">
                            <div class="color-selector" color="<?php echo $this->options->button_to_color(); ?>"></div>
                            <input type="text" class="color-value" name="<?php echo $this->options->button_to_color_name(); ?>" value="<?php echo esc_attr($this->options->button_to_color()); ?>" />
                        </div>
                        <?php
                        $description = __('Select two different colors to create a gradient.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>                       
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->button_text_color_label(); ?>
                    </th>
                    <td>
                        <div class="color-selector" color="<?php echo $this->options->button_text_color(); ?>"></div>
                        <input type="text" class="color-value" name="<?php echo $this->options->button_text_color_name(); ?>" value="<?php echo esc_attr($this->options->button_text_color()); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->open_button_color_label(); ?>
                    </th>
                    <td>
                        <div class="color-selector" color="<?php echo $this->options->open_button_color(); ?>"></div>
                        <input type="text" class="color-value" name="<?php echo $this->options->open_button_color_name(); ?>" value="<?php echo esc_attr($this->options->open_button_color()); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->close_button_color_label(); ?>
                    </th>
                    <td>
                        <div class="color-selector-div">
                            <div class="color-selector" color="<?php echo $this->options->close_button_color(); ?>"></div>
                            <input type="text" class="color-value" name="<?php echo $this->options->close_button_color_name(); ?>" value="<?php echo esc_attr($this->options->close_button_color()); ?>" />
                        </div>
                        <div class="color-selector-div">
                            <div class="color-selector" color="<?php echo $this->options->close_button_color_hover(); ?>"></div>
                            <input type="text" class="color-value" name="<?php echo $this->options->close_button_color_hover_name(); ?>" value="<?php echo esc_attr($this->options->close_button_color_hover()); ?>" />
                        </div>
                        <div class="color-selector-div">
                            <div class="color-selector" color="<?php echo $this->options->close_button_color_x(); ?>"></div>
                            <input type="text" class="color-value" name="<?php echo $this->options->close_button_color_x_name(); ?>" value="<?php echo esc_attr($this->options->close_button_color_x()); ?>" />
                        </div>
                        <?php
                        $description = __('Normal, Hover, X', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
            </table>
            <?php
        }

        public function postbox_notification_bar_css_settings() {
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php echo $this->options->dynamic_css_use_url_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->dynamic_css_use_url_name(); ?>" <?php echo $this->options->dynamic_css_use_url() ? 'checked' : ''; ?> />
                        &#160;
                        <?php
                        $description = __('Custom and dynamic CSS will be added through a URL instead of writing to the document. Enabling this setting is recommened if there are no conflicts, so that caching can be leveraged.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->custom_class_label(); ?>
                    </th>
                    <td>
                        <input class="regular-text" type="text" name="<?php echo $this->options->custom_class_name(); ?>" value="<?php echo esc_attr($this->options->custom_class()); ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->custom_css_label(); ?>
                    </th>
                    <td>
                        <textarea name="<?php echo $this->options->custom_css_name(); ?>" rows="10" cols="75"><?php echo esc_textarea($this->options->custom_css()); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo $this->options->css_enqueue_footer_label(); ?>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo $this->options->css_enqueue_footer_name(); ?>" <?php echo $this->options->css_enqueue_footer() ? 'checked' : ''; ?> />&#160;
                        <?php
                        $description = __('Enqueue CSS in footer.', 'wpfront-notification-bar');
                        $this->echo_help_tooltip($description);
                        ?>
                    </td>
                </tr>
            </table>
            <?php
        }

        protected function message_field() {
            ?>
            <tr>
                <th scope="row">
                    <?php echo $this->options->message_label(); ?>
                </th>
                <td>
                    <?php
                    $id = 'notification_bar_message_text';
                    $name = $this->options->message_name();
                    $content = $this->options->message();
                    $settings = array(
                        'wpautop' => false,
                        'textarea_name' => $name,
                        'default_editor' => 'html'
                    );
                    wp_editor($content, $id, $settings);
                    ?> 
                    <?php
                    $description = __('Use the "<b>Preview</b>" field to see the output text.', 'wpfront-notification-bar');
                    $this->echo_help_tooltip($description);
                    ?>
                </td>
            </tr>
            <?php
        }

        protected function scheduled_date() {
            ?>
            <tr class="schedule-date">
                <th scope="row">
                    <?php echo $this->options->schedule_label(); ?>
                </th>
                <td style="color:red;">
                    <p><?php echo __('Scheduling is not available in free version.', 'wpfront-notification-bar') . ' ' . sprintf('<a target="_blank" href="https://wpfront.com/notification-bar-pro/">%s</a>', __('Upgrade to Pro.', 'wpfront-notification-bar')); ?></p>
                </td> 
            </tr>
            <?php
        }

        protected function echo_help_tooltip($description) {
            $description = esc_attr($description);
            ?>
            <i class="fa fa-question-circle-o" title="<?php echo $description; ?>"></i>
            <?php
        }

        protected function script() {
            ?>
            <script type="text/javascript">
                (function () {
                    init_wpfront_notifiction_bar_options({
                        choose_image: '<?php echo __('Choose Image', 'wpfront-notification-bar'); ?>',
                        select_image: '<?php echo __('Select Image', 'wpfront-notification-bar'); ?>',
                        x_hours: '<?php echo __('%1$d hour(s)', 'wpfront-notification-bar'); ?>',
                        x_hours_minutes: '<?php echo __('%1$d hour(s) and %2$d minute(s)', 'wpfront-notification-bar'); ?>'
                    });
                })();
                (function ($) {
                    var $div = $('div.wrap.notification-bar-add-edit');
                    $(function () {
                        $div.find('.if-js-closed').removeClass('if-js-closed').addClass('closed');
                        postboxes.add_postbox_toggles('<?php echo $this->controller->get_menu_slug(); ?>');
                    });

                    $(function () {
                        $div.find('i').tooltip({
                            tooltipClass: "notification-bar-tooltip",
                            position: {my: "left+10 center", at: "right center"},
                            content: function () {
                                return $(this).prop('title');
                            },
                            hide: 50
                        });
                    });
                })(jQuery);
            </script>
            <?php
        }

    }

}
