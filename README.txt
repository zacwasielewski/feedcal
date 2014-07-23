=== FeedCal ===
Contributors: zacwaz
Donate link: http://wasielewski.org/projects/feedcal
Tags: calendar
Requires at least: 3.9.1
Tested up to: 3.9.1

WordPress plugin to display a month-view calendar with highlighted days from an RSS (or equivalent) feed.

== Description ==

FeedCal is a very simple calendar widget for WordPress. Optionally, one or more days may be highlighted by supplying the URL of an RSS or Atom feed.

Styling guide:

<table class=“feedcal-calendar">
    <caption>Month Year</caption>
    <tbody>
        <tr class="feedcal-row">
            <td class="feedcal-day-np">
                <!-- Empty cell -->
            </td>
            <td class="statical-day">
                <!-- Standard cell -->
                <div class="day-number"></div>
            </td>
            <td class="feedcal-day feedcal-highlight">
                <!-- Highlighted cell -->
                <div class="day-number"></div>
            </td>
        </tr>
    </tbody>
</table>

== Installation ==

1. Upload the `feedcal/` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Drag the widget to your desired location in the 'Appearance' / 'Widgets' menu in WordPress

== Frequently Asked Questions ==

None yet.

== Screenshots ==

1. Admin widget configuration
2. Site sidebar display

== Changelog ==