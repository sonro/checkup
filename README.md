# checkup

php utility for emailing webmasters/admins when websites are not live

## Dependencies

- php8.1+
- composer

## Install, Setup, and Usage

- Download and open the repository
- `composer install`
- `./checkup`
- Add email recipients and SMTP credentials to 'config.json'
- Add line seperated URLs to 'testsites'. These are tested on every execution
- `./checkup`
- If no errors are displayed, setup a cronjob to run './checkup' as often as you want
- A log of issues is kept in './var/log/checkup.log'

## Information

The emails are only triggered the first time a website is marked as being offline.
Each email states all the sites that are currently inaccesable by the app.
Another email is sent to annouce that all sites are functioning again.

No checks are attempted when the app can not establish a connection to the 'test_url' in 'config.json'.

## Licence

This entire work (including this document &amp; all associated source code) is
licensed to anyone under the terms of the [GNU General Public License v3.0
](https://www.gnu.org/licenses/gpl-3.0.html) (or any later version of the same
license, at the licensee&rsquo;s option). For the relevant legal text, see the
[LICENSE](./LICENSE) file.

[![GNU GPL v3+](https://www.gnu.org/graphics/gplv3-with-text-136x68.png)
](https://www.gnu.org/licenses/gpl-3.0.html)
