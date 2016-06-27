# MySQL Database
CREATE TABLE `users` (
 `username` text NOT NULL,
 `password` text NOT NULL,
 `salt` text NOT NULL,
 `yubikey` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1