=== StatiCal ===
Contributors: zacwaz
Donate link: http://wasielewski.org/projects/statical
Tags: calendar
Requires at least: 3.3.1
Tested up to: 3.3.1

Very simple static calendar widget for WordPress. Year, month, and specific days may be highlighted.

== Description ==

StatiCal is a very simple static calendar widget for WordPress. It allows the user to specify a title, year, and month calendar to display. Optionally, one or more days may be highlighted.

Styling guide:

<table class="statical-calendar">
    <caption>Month Year</caption>
    <tbody>
        <tr class="statical-row">
            <td class="statical-day-np">
                <!-- Empty cell -->
            </td>
            <td class="statical-day">
                <!-- Standard cell -->
                <div class="day-number"></div>
            </td>
            <td class="statical-day statical-highlight">
                <!-- Highlighted cell -->
                <div class="day-number"></div>
            </td>
        </tr>
    </tbody>
</table>

== Installation ==

1. Upload the `statical/` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Drag the widget to your desired location in the 'Appearance' / 'Widgets' menu in WordPress

== Frequently Asked Questions ==

None yet.

== Screenshots ==

1. Admin widget configuration
2. Site sidebar display

== Changelog ==

= 0.2.0 =
Major rewrite of codebase to follow the conventions outlined by WordPress-Widget-Boilerplate.

https://github.com/tommcfarlin/WordPress-Widget-Boilerplate

= 0.1.0 =
* Initial commit
