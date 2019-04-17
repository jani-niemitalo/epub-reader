# Ebook-Reader
Online reader / library for .epub files.

Features:

* List all E-Books in nice and comfortable UI with E-Book's cover as a thumbnail and a title.
* Search E-Books from list, based on Title, or author (TODO: Search by series)
* Preview E-Book contents, (Thumbnail, description, Author, Title, Publisher, ISBN)
* Read ebook in intuitive 'buttonless' user interface Paginated on Desktop, scrollable on mobile.
* Server saves progress, and recently read ebooks in databse, to display on top of library, for easy resuming, on any device.
* Login and accounts managed by Google login, and the website security SHOULD be relatively OK, atleast there is no password leaks, because no passwords are stored in the database, or anywhere to that matter.
* Simple permission system is in place (TODO: Will change to more 'usefull' one in future, if i can bother)
* *  Quests     (people who just login with google without contacting website owner) have access to top ~25 Public Domain Ebooks accuired from (http://www.gutenberg.org/browse/scores/top) 
* * Users:      The "standard" users who have access to all ebooks on the server
* * Uploaders   No difference at this point
* * Admin:      Can force database to reload, and can change book permissions from book preview window

The Folder structure of this project is absolutely horendous, and i am aware of that, it is partly because i made this in a rush, and mainly for myself for personal use. And partly because a library that i am using does not support accessing parent folders at all, so some files must be at the top directory, or other spesific files need to be in subdirectories of that one, and it becomes a whole mess. In commit history you can observe my attempt at organizing this to folders, which crashed and burned gloriously.

NOTE: This is a Mirror Repository of my actual production repository which has CI and all fancy stuff, so this might not be updated as often.
