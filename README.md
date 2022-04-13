# moodle-block_newcourses

General Information

This block allows teachers to display courses enrolled to them (with teacher role). 
if the teacher hasn't been enrolled in any courses, he is informed on the main page by alert (as block).
If the teacher has been enrolled to any course, he can go to page with the list of new courses from alert on the main page.
It is useful because it helps with working in courses by the teacher (who is informed about new courses and won't skip them).

This block looks up the database for teacher enrolments (as teacher role) and checks if teacher viewed these courses.
If not, they will show up in the table.
IMPORTANT: the block is searching that data in 30-day period. Searching over a longer period of time adversely affects the server's performance.
It means that if the teacher has been enrolled over 30 days, it won't be included.

Required version of Moodle

This version works with Moodle 3.5 and above.

Installation (https://docs.moodle.org/20/en/Installing_plugins)

1. Go to the Moodle plugins directory and download the ZIP file.
2. Upload or copy it to your Moodle server.
3. Unzip it in the right place for the plugin type (or follow the plugin instructions).
4. In your Moodle site (as admin) go to Site administration > Notifications

Privacy

This block only display information and does not store personal data.

Author

The module has been written and is currently maintained by Grzegorz Bębenista <gbebenista@ahe.lodz.pl>
The block has been made to use for Platforma Zdalnego Nauczania (e-learning platform) maintained by Polski Uniewersytet Wirtualny (PUW)


License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see http://www.gnu.org/licenses/.
