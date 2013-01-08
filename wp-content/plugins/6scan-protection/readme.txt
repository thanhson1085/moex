=== 6Scan Security ===
Contributors: 6Scan
Version: 2.3.1
Tags: security,secure,wordpress security,firewall,antivirus,security plugin,securty,protection,anti-hack,hack,scan,exploit,anti-virus
Requires at least: 3.0.0
Tested up to: 3.5
Stable tag: trunk

6Scan Security provides enterprise-grade security with a firewall, automatic backup, analytics and much more.

== Description ==

6Scan Security is the most comprehensive *automatic* protection your Wordpress site can get against hackers.  Our security scanner goes beyond the rule-based protection of other Wordpress security plugins, employing active penetration testing algorithms to find security vulnerabilities.  These are then automatically fixed before hackers can exploit them. Our team of website security experts ensures your protection is always up-to-date and airtight.

Our automatic security scanner finds and protects against:

* SQL Injection
* Cross-Site Scripting (XSS)
* CSRF
* Directory traversal
* Remote file inclusion
* Several DoS conditions
* And many more, including all of the OWASP Top Ten security vulnerabilities.

6Scan Security includes an agent that runs on your server to rapidly fix all security vulnerabilities found by the scanner.  Our team of security experts constantly finds new vulnerabilities and attack strategies, and integrates them into the scanner so you are immediately protected.

6Scan Security also includes a Web Application Firewall (WAF) that uses pattern matching to block out even more security threats.  Our application firewall is completely configurable so you can choose the level of security you desire for your site.

6Scan Security also includes protection against brute-force password hacking and dictionary attacks.  After a configurable number of failed logins from a remote IP, that IP can be blocked for a predefined period of time.  An email notification of the usernames tried can optionally be sent to the site's administrator.  To enable the login security feature, click the Settings tab on your dashboard and select the appropriate checkbox.  You will also be able to select the timeouts/periods and thresholds for a security lockdown.

Once 6Scan Security is installed, no further action is required to keep your site protected.  6Scan Security is also specifically engineered not to affect your site's performance or interfere with your site's legitimate users.  
Our dashboard is specifically designed to convey your security status in a clear and simple manner, so that even non-experts can understand the situation.

It is very important to take note of the difference between various Wordpress security plugins.  Most of these are based on a ruleset which recognizes and blocks certain attack signatures. This approach is effective for protecting against some common SQL injection attacks, but fails to detect or prevent hackers from exploiting flawed logic.  For example, it could not protect against an authorization bug in a file upload plugin, potentially allowing unauthorized users to upload malware and viruses to your site.  6Scan's security response team constantly updates your blog's protection to deal with the latest threats found on all major exploit databases on the Internet.

To make sure you are always secure, we are constantly updating 6Scan Security with new features.  The following features are in beta testing and will be released soon:

* Suspicious Traffic Graphs: See a visualization over time of exactly how many hackers are attempting to exploit security vulnerabilities on your site and gain unauthorized access.  For example, we will show you bots, automated hacking tools, security audit software, etc. - anything filtered by our WAF (Web Application Firewall) or by one of the security vulnerabilities you have fixed.  You can also see how much of your traffic is not malicious.
* Secure Login: A plethora of options you can use to protect against login attacks (such as dictionary and brute force attacks).  Lock would-be attackers out after a number of unsuccessful login attempts, receive email notifications when someone is trying to hack your site, and much more!
* Automatic Secured Backup: 6Scan can back up your entire site and database.  If anything should ever go wrong - whether it's a security problem or just a mistake you made - you can easily download a backup and undo the change.  You don't even need an antivirus or antimalware product!  Backups are encrypted on-the-fly and stored on our secure servers so nobody can access them but you.

Let 6Scan handle the security of your Wordpress site, so you can worry about what really matters to you - your content.  If you have any questions, please feel free to contact us using our [support area](http://6scan.com/support).

== Installation ==

In order to install 6Scan Security, please follow these steps:

1. Upload the ZIP file containing the plugin to your Wordpress site, using the "Add New"->"Upload" option on the Plugins screen (or automatically find us by searching for "6Scan").
1. When the plugin has been installed, click to activate the plugin.
1. Once activated, 6Scan Security will display a message informing you how to activate protection.

Please note that 6Scan Security requires read/write access to your .htaccess file, which allows us to intercept and analyze/block suspicious requests before they reach any vulnerable script.  If you do not have this access, installation of the plugin may fail.

To allow 6Scan Security to constantly update its signatures and keep you protected, we require the fopen or curl library to be installed and enabled.  If you have a file-monitoring extension, you may see frequent changes in the 6Scan signature files; this is normal and is an indication that your protection is working properly and up to date.

Once you register with 6Scan, we will automatically send you an email notification when our scanner detects a threat or another problem with your website. To change the notification options (or completely unsubscribe from these messages) please visit the 6Scan Settings panel.  You may also elect to be notified of new threats or problems by SMS to any destination worldwide.

Once installed, 6Scan Security will add three items to your Wordpress menu: Dashboard, Settings and Support.

The dashboard shows you the list of security vulnerabilities detected by our scanner. Every security issue can be clicked for more information. There is a textual description of each vulnerability and a link to a public advisory (when available) on a Bugtraq site.

The settings page allows you to configure the following Web Application Firewall security options:

* SQL Injection - Detects and blocks database hacking attempts
* Cross Site Scripting (XSS) - Detects and blocks identity theft attacks, which are based on stolen cookies
* Disable Non-standard request types - Disables any non-GET/POST requests to your site
* Remote File Inclusion protection - Disables requests that include a path to an external PHP file, which could be a major security risk
* CSRF protection for POST requests - Detects and blocks POST requests originating from foreign domains

The support area allows you to ask questions, report bugs or consult on security-related matters.  If you need to add confidential details to your support request (such as passwords), you may email us at support@6scan.com instead of posting on our public support forum.  All tickets received at support@6scan.com are handled with discretion and your information will only be shared with employees on a need-to-know basis to help solve your problem.

In order to uninstall the 6Scan Security plugin:

1. Open the Plugins menu item on your Wordpress admin area
1. Next to the 6Scan Security plugin, click Deactivate, then Delete
1. When the plugin is deleted, any active 6Scan subscription will be automatically deactivated, along with any email or SMS notifications you have configured.

Please note that if you uninstall 6Scan Security, any fixed security vulnerabilities will lose their fixes.  If 6Scan Security has fixed vulnerabilities on your site, you must keep the plugin installed to keep these fixes active.

If you encounter any problems during installation, please visit our [support area](http://6scan.com/support) or email us at support@6scan.com.

== Frequently Asked Questions ==

= Does 6Scan Security work with other security plugins? =

Yes, 6Scan Security has been tested with many other security, antivirus, firewall and backup plugins and does not conflict with them. If you suspect any compatibility problem, please contact us via our [support area](http://6scan.com/support) or email support@6scan.com.

= Will 6Scan Security work with my hosting package? =

We work with all standard hosting packages that support Wordpress.  We have specifically tested 6Scan Security with many popular hosting companies, including GoDaddy, Hostgator, Dreamhost, Site5, 1&1 and others.  Of course, more advanced configurations such as VPS/VDS are also supported, as long as your file permissions are configured correctly (see the Installation section for more details).

= I get the error "Can't create signature file" or "Can't update .htaccess file" when installing the plugin =

6Scan requires write permissions to your web root directory and .htaccess file in order to install the automatic fix signatures.  For more information on how to enable write access, please see http://codex.wordpress.org/Changing_File_Permissions .

= What web servers does 6Scan support? =

6Scan Security currently works with any server that has .htaccess and mod_rewrite support, such as Apache and IIS.  This is required, so that 6Scan could intercept and analyze requests before they reach server and potentially vulnerable scripts.  Support for Nginx is planned in the future.

= Does 6Scan affect my site's performance? =

We pay specific attention to our plugin's performance because it should work seamlessly, even under heavy load.  Because our initial flagging rules are optimized to be lightning fast, and only suspicious requests undergo additional checks, your site's legigimate users will not be affected.

= Does 6Scan protect against TimThumb vulnerability? =

TimThumb is an RFI vulnerability, which is based on including a malicious PHP script as a path to your TimbThumb gallery.  It is easily filtered out by 6Scan Security's Web Application Firewall.  One of the advantages of the application firewall rules is that they are complete generic, and will block out TimThumb wherever it is on your site, as well as automatically blocking similar vulnerabilities in the future.

= What is the 6Scan WAF feature? =

WAF is an acronym for Web Application Firewall.  It is a set of rules which are designed to flag suspicious requests and then act accordingly (for example, by blocking the request before it reaches its target).  Our firewall is written to match a set of widespread attacks patterns, while minimizing its impact on user experience.

= How often does 6Scan Security scan my site for the newest security threats? =

On average, your site will be scanned once every few hours, making sure your site is scanned several times every day for the latest security issues.  However, when a new vulnerability is discovered and published, 6Scan Security will scan affected sites with a higher priority to make sure the vulnerability is fixed right away.

= How quickly does 6Scan find and protect against new exploits? =

We monitor all the large exploit databases 24/7, which allows us to respond immediately to any publicly published exploit.  Our security research team also analyzes Wordpress and plugin code to find vulnerabilities even before they are known to the general public.  Finally, we use honeypots - special traps designed to lure hackers in - to gather information about new techniques hackers try, and those techniques are immediately found and fixed on your site.

When you have 6Scan installed, you do not need to worry about a newly found exploit for Wordpress or any of your installed plugins - we follow security newsfeeds for you and release a fix before hackers find out about and exploit new vulnerabilities.

= Why should I choose 6Scan Security and not any other available security plugin? =

First, because other plugins do not protect against all the security vulnerabilities we can.  Most other plugins are based on a ruleset which recognizes and blocks certain attack signatures. This approach is effective for protecting against some common SQL injection attacks, but fails to detect or prevent hackers from exploiting flawed logic.  For example, it could not protect against an authorization bug in a file upload plugin, potentially allowing unauthorized users to upload malware and viruses to your site.  6Scan's security response team constantly updates your blog's protection to deal with the latest threats found on all major exploit databases on the Internet.

Second, because 6Scan Security is easy-to-use, so that anyone - even without a technical background - can understand and use our plugin to fix security problems.  Our plugin is easy to activate, very user-friendly but still extremely efficient.

= What is a zero-day security vulnerability? =

A zero-day vulnerability is a security flaw which has been found by hackers, but has not yet been patched by the vendor of the affected component, making it an easy target for hackers.  In fact, most hackers operate by taking the latest zero-day vulnerabilities and scanning the entire web for sites which have them!  A general firewall or antivirus product will not protect you against many zero-day attacks since new attacks might not match any currently known pattern.

Once the vendor has released an update, the vulnerability is no longer classified as 'zero-day', but websites must still update the affected component before they are secure.  6Scan Security protects you against zero-day vulnerabilities immediately after they are found and without forcing you to update any components.

= Why is it important to fix security vulnerabilities? =

Hackers are constantly on the prowl for sites they can exploit.  Security vulnerabilities are the hacker's method of gaining unauthorized access to sites.  Once they do, they can steal data, deface pages, install spyware or botnets, and perform other malicious actions against the website and its users.  Only by making sure your site does not have any vulnerabilities can you secure yourself against these hackers.

= What other security measures should I employ? =

* Password strength: 6Scan Security protects your website against hackers, but nobody can protect against a hacker who can guess your password.  Always use a complex password that contains letters of different cases, numbers and punctuation.  Never use a dictionary word, names of loved ones, or birthdays as passwords, as hackers can easily find them out.
* Spyware: if your computer is infected with spyware or other malware, it may steal your passwords from you without you even knowing!  Always make sure to have current versions of anti-spyware, antivirus and antimalware products active on your computer.  Never log in to your site from a public computer, such as a computer in a public library, as these are frequently compromised with malware designed especially to steal passwords as they are entered.
* Access through HTTPS on public networks: If your website's login form does not use HTTPS, your login details can easily be intercepted as they pass through public networks, such as WiFi in a coffee shop or a public library network.  If you must log in from a public network, be sure your login form uses HTTPS encryption.

= Can 6Scan Security fix a site that has already been hacked? =

6Scan Security protects you from hackers attempting to compromise your site, but it cannot undo the damage a hacker has already caused - it is not an antivirus, but a preemptive protection solution.  Any damage must be manually cleaned before 6Scan can effectively secure your site.  Our backup feature helps you by ensuring that even if your site is compromised, you will always be able to roll back to a clean and secure version with a minimum of hassle - no antivirus or antimalware required.

= 6Scan scanned my site and no vulnerabilities were found. What does this mean? =

Good news!  This means that there are no immediate security problems with your site.  However, you should still keep 6Scan Security installed so it can continue to monitor your site.  It is quite possible that one of your site's components has a security vulnerability which hasn't yet been discovered.  Once it is discovered (either by our security research team or by another party), 6Scan Security will notify you and allow you to patch it before hackers use it to compromise your site.

= How is 6Scan Security different from an antivirus or antimalware product? =

Antivirus and antimalware products are designed to let you know when your site is infected by a virus or malware, and help you remove it.  However, the existence of a virus or malware on your site means it has already been compromised by hackers!  6Scan Security prevents hackers from getting into your site in the first place, meaning you will never have malware installed.  However, 6Scan does include a malware scanner that will let you know if there is any pre-existing malicious code on your site.

= How am I notified if new security vulnerabilities are found on my site? =

You can be notified in three different ways:

* An email message.
* A text (SMS) message.
* A notification on your Wordpress dashbord.

To set your notification preferences, simply open your 6Scan Security dashboard, click the Settings tab, and check or uncheck the boxes under Notifications.

= How do I unsubscribe from email notifications? =

Easy!  Open your 6Scan Security dashboard, click the Settings tab, and uncheck the email box under Notifications.  You will no longer receive new vulnerability notifications by email.

= What is the backup feature? =
	
In addition to our security features, we have also added automatic scheduled backups for your Wordpress site.  The backup feature makes sure that even in case of an accidental deletion, server problem, or even lost password, you will be able to restore a working and secure version of your site.

Our automatic backup runs automatically on a schedule, backing up both your database and your site's files to our secure cloud datacenter.  A number of previous backups can be stored, ensuring you can go back to a number of points in time.  You can download the backups from your 6Scan dashboard; backups are secured, and their download is protected by a key, so only you can download them.

= I have a feature request! =

We are always open to feature requests, especially for security-related features. Please contact us with a detailed description of your request at our [support area](http://6scan.com/support), and we will consider including it in our plugin.

= Who is 6Scan? =

We are a team of ex-military security experts who have implemented traditional expensive and complicated website security solutions.  We couldn't find a way to effectively secure small and medium websites with lower budgets and no technical expertise - which is why we decided to create a Wordpress plugin that's both comprehensive and easy-to-use.

== Screenshots ==

1. Your dashboard shows the security vulnerabilities found on your site.  6Scan can automatically and immediately fix each of these vulnerabilities, ensuring your site is always secure.
2. Security settings of 6Scan's WAF and notification of new security vulnerabilities by SMS.
3. If you prefer to manually fix a security vulnerability, you can get detailed instructions on how to do so.
4. Security analysis

== Changelog ==

= 1.0.1 =
* Initial alpha release.

= 1.0.2 =
* Error reporting form added.
* If install fails, user now sees better error description.
* Fixed a bug that could occur when installing the plugin on servers with an empty or outdated root CA list.

= 1.0.3 =
* Bugfix, regarding access to 6Scan's SSL server.

= 1.0.4 =
* Gate script now works correctly with servers, that have DOCUMENT_ROOT different from the real document root (like 000webhost).
* More sanity checks before installing (checking for openssl_* functions, required php.ini flags, and more).
* Added helpful links to errors that might occur while installing.
* Now verification file resides on server as long as 6Scan Security is installed.

= 1.0.5 =
* 6Scan Security Plugin has an easier to use activation feature
* Support submenu added
* Htaccess rules have been changed to tighten the security even more
* Fixed few bugs, which could occur under Windows server environment

= 1.0.6 =
* Now supports curl transport, if fopen() fails
* Improved communication with 6Scan server

= 1.0.7 =
* Installation process improved.
* Added settings menu
* Added support for more security scanning servers

= 1.0.8 =
* Security tightened even more
* Small bugfixes

= 1.0.9 =
* Adjusted signature update protocol for new API 

= 1.0.10 =
* Site verification process improved

= 2.0.1 =
* Smoother install process
* Displays vulnerability count
* Added patch to work with very slow servers

= 2.1.1 =
* Added WAF security settings
* Added manual fix instructions for security vulnerabilities
* New dashboard design
* Added new feature: login security.  Login security can optionally lock out users who attempt a brute-force or dictionary attack on your blog's login form.

= 2.1.2 =
* In addition to website security, we have introduced a backup feature, allowing users to automatically create backups of their database and files. The backups are securely uploaded to our cloud datacenter and are only accessible by the site owner.
* Changed the UI of the ticket submission form.
* UI minor bugfix: on site verification failure, the message to user was double escaped.
* Some servers had security settings that denied long GET requests.  A fix was introduced to avoid this condition.

= 2.1.3 =
* Added another security check to CSRF on POST check. Now empty referrers are considered safe, because some user agents do not pass the referrer at all (for security or privacy reasons).
* Changed server communication protocol when performing backups for more reliability.
* Error messages have been rewritten to be more clear.
* Can now connect to MySQL database through socket.
* Added support for non-legacy tar implementations.
* Fixed: login security could sometimes lock-out users that were using XML-RPC to make posts.
* Storage upload engine was completely rewritten.
* Backup feature now makes sure that no old backups are left in the Wordpress directory (otherwise they could stack and inflate the backup size).

= 2.1.4 =
* Fixed a bug in a gatekeeper script, where a special configuration would cause scripts to get the wrong value from the PHP_SELF variable.
* Older versions of Wordpress would sometimes not update security signatures. Fixed that condition.
* Fixed a bug where WAF security options would sometimes not act as intended.

= 2.1.5 =
* If a security vulnerability has been discovered, it is now shown on the Wordpress administrator panel.
* Fixed: under certain configurations, server firewalls could mistake a backup request for a security threat and block it.
* Fixed a bug where some servers would add their html code to scripts' output and confuse the 6Scan plugin.

= 2.2 =
* Worked around a problem with WP_Filesystem that many users saw during installation.  This problem could pop up if the file ownership on some of your files is not as Wordpress requires.  6Scan Security now installs and functions correctly even if WP_Filesystem does not, although correct file permissions are still required.
* Fixed minor UI discrepancies.
* Optimizations to secure automatic backup feature.

= 2.2.1 =
* We have added a pure PHP implemented fallback for openssl_verify function, so that if your webhosting does not have openssl package, you can still use 6Scan without compromising on traffic security.

= 2.2.3 =
* We have added a full support for WP_Filesystem. If wordpress is running without permissions to access filesystem, user is required to enter the FTP credentials (Based entirely on Wordpress filesystem implementation)
* Added a 6Scan Security dashboard widget
* We also make sure to set a correct permissions mode on our verification file (There are some servers, that create it without runnable permissions by default)

= 2.2.5 =
* Now running pure PHP code, when performing database backup. Now database backup has much less prerequisites

= 2.2.7 =
* Solved permission issues, while changing .htaccess. There could be an error, of wp_filesystem was initialized to other than 'direct'.

= 2.2.8 =
* Changed path references. Now the are referenced as $wp_filesystem->abspath() and alike (The ABSPATH define is only used in several 'direct' access parts)

= 2.3.0 =
* Fixed a bug during install with wp_filesystem()
* When user clicks "Activation" he sees a local page with terms, textbox for his email address and an "Install" button. Registration data (user's email and url) will be passed to 6Scan server only after user clicks Install. 
* Supports Wordpress 3.5

= 2.3.1 =
* Minor bugfixes


== Upgrade Notice ==

* Support menu, if user encounters a problem
* Security tightened up even more
* Easier to install
* Built-in firewall to keep hackers out and your site secure.
* Login security to stop brute-force and dictionary attacks.
* Automatic scheduled backups, stored in our secure datacenter.
* Supports latest Wordpress