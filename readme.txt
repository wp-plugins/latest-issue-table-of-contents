=== Plugin Name ===
Contributors: Martin Hurford
Donate link: http://webdesign.mindtripz.com/wordpress-plugin-latest-issue-table-of-contents/
Tags: table of contents, latest issue, tag, magazine
Requires at least: 3.0.1
Tested up to: 3.0.1
Stable tag: 1.0

Displays a 'table of contents' in your sidebar containing the post titles in your latest issue.

== Description ==

Displays a 'table of contents' in your sidebar containing the post titles in your latest issue.
Posts are grouped by and can be ordered by category.

Post Tags are used to define which issue a post belongs to,
i.e a tag of 'Issue 10' denotes the 10th issue, easy eh?

The tag prefix can be any word you like as long as it is consistently used across issues
and contains a numeric identifier which is incremented for each new publication. A tag suffix
can also be used, e.g. a tag named '100 MyDomainName' is the 100th issue of suffix 'MyDomainName'

The table of contents will automatically show the contents of a previous issue
when a post or tag archive from that issue is accessed,
i.e. The current issue is 5 but when a visitor accesses my tag archive for issue 3
the table of contents for issue 3 is displayed.
If I view a post in issue 2 (say from a direct link that was e-mailed to me) I will see the
table of contents for issue 2.

= Example =

I have 100 posts, 10 make up each publication.
In my first publication all posts will be tagged 'Publication 1'.
All subsequent publications will retain the tag prefix and increment the numeric identifier.
The posts in my second publication (a week later) will then be tagged 'Publication 2' and
my next publication (after another week) will be tagged 'Publication 3'

== Installation ==

The automatic plugin installer should work for most people.

To manually install the plugin:

1. Upload the plugin folder to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Who is it for? =

The widget is designed for those who like to publish posts in a magazine or periodical style
i.e. publish 'x' number of posts on the nth day of each month.

= How do I use it? =

The widget takes these arguments:

Title
> Enter the title you would like displayed, if left blank it defaults to the latest tag

Prefix/Suffix
>This is the part of your tag which doesn't change from issue to issue e.g.

-  Your tags are 'Publication 1','Publication 2' etc. so your prefix is 'Publication'
-  Your tags are 'Issue 1','Issue 2' etc. so your prefix is 'Issue'
-  Your tags are '1 My Mag','2 My Mag' etc. so your suffix is 'My Mag'

>  You get the idea...

Category
> Check the box to include the category in your table of contents.
If no posts are in the category for the selected tag prefix then the category is not displayed

Order
> The order in which you would like the categories displayed with 1 being at the top under the title.
If no order is entered the category is not displayed even if the checkbox is checked.

== Screenshots ==

1. The Widget Admin

<img src="/tags/1.0/litoc_screenshot-1.jpg" />

== Changelog ==

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.0 =
* Initial release

== Arbitrary section ==

In addition to the widget a shortcode is available for you to easily display a list of your tag archives.

[LITCO:prefix:LITCO] - Where 'prefix' is the prefix of your identifying tag as previously described.

When placed in the post or page of your choice this shortcode will expand to display an ordered list
starting from the lastest issue minus one i.e. the issue previous to the current issue.
