-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db.3wa.io
-- Généré le : mer. 23 août 2023 à 08:18
-- Version du serveur :  5.7.33-0ubuntu0.18.04.1-log
-- Version de PHP : 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `julienthomas_monpetitbouchon`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, 'Vin rouge'),
(2, 'Vin rosé'),
(3, 'Vin blanc');

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE `contacts` (
  `id` smallint(3) UNSIGNED NOT NULL,
  `subject` varchar(50) NOT NULL,
  `email` varchar(160) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processed` varchar(20) NOT NULL DEFAULT 'off'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `contacts`
--

INSERT INTO `contacts` (`id`, `subject`, `email`, `content`, `created_at`, `processed`) VALUES
(2, 'titi', 'titi@gmail.com', 'test', '2023-06-17 15:43:33', 'off'),
(8, 'Question d\'ordre général', 'mapave1597@fitwl.com', 'blablabla', '2023-07-03 14:38:48', 'off'),
(9, 'Question d\'ordre général', 'mapave1597@fitwl.com', 'ddddddddddddddddddddddddddddd', '2023-07-03 15:02:45', 'off'),
(10, 'Question d\'ordre général', 'mapave1597@fitwl.com', 'test', '2023-07-03 16:29:55', 'off'),
(13, 'Question d\'ordre général', 'mapave1597@fitwl.com', 'ffffffffffffffffff', '2023-07-04 10:18:45', 'on'),
(15, 'Question d\'ordre général', 'ju@gmail.com', 'test', '2023-08-21 09:50:40', 'off'),
(16, 'Question d\'ordre général', 'c@c.cc', 'c', '2023-08-22 14:15:27', 'off'),
(17, 'Question d\'ordre général', 'vv@vv.vv', 'v', '2023-08-22 14:21:43', 'off');

-- --------------------------------------------------------

--
-- Structure de la table `opinions`
--

CREATE TABLE `opinions` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `score` smallint(2) UNSIGNED NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `title` varchar(20) NOT NULL,
  `opinion` longtext NOT NULL,
  `products_id` smallint(5) UNSIGNED NOT NULL,
  `users_id` smallint(5) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(5) NOT NULL DEFAULT 'on'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `opinions`
--

INSERT INTO `opinions` (`id`, `score`, `pseudo`, `title`, `opinion`, `products_id`, `users_id`, `created_at`, `status`) VALUES
(21, 4, 'L\'amateur', 'très bon', 'bon rapport qualité prix', 72, 12, '2023-07-12 15:09:34', 'on'),
(23, 3, 'Le testeur', 'bon millésime', 'notes fumées et toastées, bouche ample et fruitée, belle matière et une certaine fraîcheur.', 68, 12, '2023-08-22 09:58:40', 'on'),
(24, 5, 'qq', 'qq', 'qqqqq', 72, 12, '2023-08-22 10:27:58', 'on'),
(25, 5, 'test', 'test', 'test', 72, 12, '2023-08-22 14:15:00', 'on');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `orderDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) NOT NULL DEFAULT 'Commande confirmée',
  `total_price` float NOT NULL,
  `qty_total` smallint(5) NOT NULL,
  `users_id` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `orderDate`, `status`, `total_price`, `qty_total`, `users_id`) VALUES
(16, '2023-07-10 18:13:43', 'Commande confirmée', 261.25, 9, 12),
(17, '2023-07-10 18:17:38', 'Commande confirmée', 520.45, 8, 12),
(18, '2023-07-10 22:02:09', 'Commande confirmée', 91.9, 1, 12),
(19, '2023-07-12 10:09:17', 'Commande confirmée', 75, 3, 12),
(22, '2023-07-12 11:05:01', 'Commande expédiée', 125, 5, 12),
(23, '2023-07-12 11:06:39', 'Commande livrée', 125, 5, 12),
(24, '2023-07-12 11:45:45', 'Commande expédiée', 25, 1, 12),
(25, '2023-07-12 11:46:20', 'Commande en préparation', 100, 4, 12),
(26, '2023-07-13 15:22:51', 'Commande expédiée', 276.5, 10, 2),
(27, '2023-08-14 10:50:42', 'Commande en préparation', 373.6, 5, 12),
(28, '2023-08-21 18:52:30', 'Commande confirmée', 330.65, 5, 12),
(29, '2023-08-21 20:59:37', 'Commande expédiée', 151.5, 9, 3);

-- --------------------------------------------------------

--
-- Structure de la table `orders_details`
--

CREATE TABLE `orders_details` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `orders_id` smallint(5) UNSIGNED NOT NULL,
  `products_id` smallint(5) UNSIGNED NOT NULL,
  `qty` int(5) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `orders_details`
--

INSERT INTO `orders_details` (`id`, `orders_id`, `products_id`, `qty`, `price`) VALUES
(1, 16, 64, 2, 18),
(2, 16, 68, 3, 48.95),
(3, 16, 67, 4, 19.6),
(4, 17, 68, 5, 48.95),
(5, 17, 72, 3, 91.9),
(6, 18, 72, 1, 91.9),
(7, 19, 56, 3, 25),
(10, 22, 56, 5, 25),
(11, 23, 56, 5, 25),
(12, 24, 56, 1, 25),
(13, 25, 56, 4, 25),
(14, 26, 70, 3, 19.35),
(15, 26, 71, 2, 60.85),
(16, 26, 73, 5, 19.35),
(17, 27, 72, 3, 91.9),
(18, 27, 68, 2, 48.95),
(19, 28, 72, 2, 91.9),
(20, 28, 68, 3, 48.95),
(21, 29, 73, 2, 19.35),
(22, 29, 64, 3, 18),
(23, 29, 74, 4, 14.7);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` longtext NOT NULL,
  `stock` smallint(5) NOT NULL,
  `price` float NOT NULL,
  `title` varchar(50) NOT NULL,
  `grape` varchar(50) NOT NULL,
  `picture` varchar(100) NOT NULL,
  `country` varchar(50) NOT NULL,
  `vintage` smallint(5) NOT NULL,
  `categories_id` smallint(5) UNSIGNED NOT NULL,
  `selected` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `stock`, `price`, `title`, `grape`, `picture`, `country`, `vintage`, `categories_id`, `selected`) VALUES
(56, 'Château test 2023', 'test ajout', 0, 25, 'Bordeaux', 'Syrah', 'public/uploads/64a3ec0868c25-Orsan_2019.png', 'France', 2023, 1, 0),
(61, 'Château Lagrugère 2020', 'Une robe rouge grenat, un nez gourmand sur les fruits mûrs, une bouche ample, riche emplie de fraicheur avec une légère astringence sur des notes de mûre, poivre, réglisse. Une belle longueur. Un vin à garder encore quelques temps pour le savourer en toute quiétude.', 50, 13.8, 'Bordeaux', 'Merlot, Cabernet Sauvignon, Petit Verdot', 'public/uploads/64a9be6749eca-Lagrugere_2020.jpg', 'France', 2020, 1, 0),
(62, 'Château Luby 2017', 'En bouche ce vin rouge est un vin puissant avec un bel équilibre entre l\'acidité et les tanins. Ce vin s\'accorde généralement bien avec de la volaille, du bœuf ou du veau.', 50, 15.75, 'Bordeaux', 'Cabernet franc, le Cabernet-Sauvignon et le Merlot', 'public/uploads/64e5bf391ae3a-Luby_2017.jpg', 'France', 2017, 1, 0),
(63, 'Château La Grande Métairie 2018', 'Une extraction douce des tanins du cabernet-sauvignon a donné ce vin souple et bien équilibré, frais et léger, qui privilégie le fruit à la structure et qui s\'offre dès à présent à la dégustation. Tout indiqué pour un plateau de charcuterie', 50, 17.45, 'Bordeaux', 'Merlot, Cabernet Sauvignon', 'public/uploads/64a9c01d48892-La_Grande_Metairie_2018.jpg', 'France', 2018, 1, 0),
(64, 'Château Bois de Favereau 2018', 'Vin coloré, bouquet riche, combinant une finesse tannique et une longueur en bouche persistante. Ce vin est élevé avec staves pendant 12 mois.', 47, 18, 'Bordeaux Supérieur', 'Merlot, Cabernet Sauvignon, Cabernet Franc', 'public/uploads/64a9c13a2f3eb-Bois_de_Favereau_2018.jpg', 'France', 2018, 1, 0),
(65, 'Château Thuillac 2015', '\"Cuvée Les Bordes\"\r\nEn bouche ce vin rouge est un vin puissant avec un bel équilibre entre l\'acidité et les tanins. Ce vin s\'accorde généralement bien avec de la volaille, du boeuf ou du veau.', 50, 20.2, 'Côtes de Bourg', 'Merlot, Cabernet Sauvignon, Malbec', 'public/uploads/64a9c1e1bdd8a-Thuillac_2015.png', 'France', 2015, 1, 0),
(66, 'Château La Grande Métairie 2020', 'Avec ce Grande Métairie blanc 2020, on retrouve au nez des notes d\'agrumes, de fruits exotiques et de fleurs blanches. La bouche est ample, avec une belle persistance aromatique, pour un ensemble rond et équilibré.', 50, 17.7, 'Bordeaux', 'Sauvignon, Muscadelle', 'public/uploads/64a9c28ca6a48-La_Grande_Metairie_blanc_2020.jpg', 'France', 2020, 3, 0),
(67, 'Château Chanteloiseau 2019', 'Le Graves Blanc du Château Chanteloiseau est un vin blanc de la région de Bordeaux. En bouche ce vin blanc est un vin puissant avec une belle fraicheur. Ce vin s\'accorde généralement bien avec du poisson gras, des fruits de mer ou du fromage doux.', 50, 19.6, 'Graves', 'Cabernet Sauvignon, Merlot, Cabernet Franc', 'public/uploads/64a9c30eb494f-Chanteloiseau_2019.jpg', 'France', 2019, 3, 0),
(68, 'Château De Rochemorin 2016', 'Ce Pessac-Léognan blanc se distingue par des notes fumées et toastées, une bouche ample et fruitée, une belle matière et une certaine fraîcheur. Il est à garder dans les 5 ou 6 ans.', 45, 48.95, 'Graves Pessac Léognan', 'Merlot, Petit Verdot, Cabernet Franc', 'public/uploads/64a9c393a21bb-Rochemorin_2016.jpg', 'France', 2016, 3, 1),
(69, 'Château La Grande Métairie 2020', 'Ce Bordeaux Rosé attire l’œil par sa couleur légère, idéal pour accompagner grillades, plats exotique et paellas mais aussi parfait pour l\'apéritif.', 50, 17.6, 'Bordeaux Rosé', 'Cabernet Sauvignon, Cabernet Franc, Merlot', 'public/uploads/64a9c421d2300-La_Grande_Metairie_rosé_2020.jpg', 'France', 2020, 2, 0),
(70, 'Château d’Orsan 2019', 'Un vin de terroir, qui a une âme et une typicité, puissant et élégant il séduira les amateurs de vin par sa finesse. Prêt à déguster, ce vin peut aussi se conserver quelques années en bouteille.', 47, 19.35, 'Côtes du Rhone', 'Grenache, Syrah et Carignan', 'public/uploads/64a9c4ee68462-Orsan_2019.png', 'France', 2019, 1, 0),
(71, 'Maison E. Guigal 2018', 'Un vin puissant et profond, joliment épicé aux notes de réglisse, de garrigue et de fruits noirs. La longueur est remarquable, la structure est homogène. La Maison Guigal signe de nouveau un grand vin de la Vallée du Rhône méridionale : Gigondas Le compagnon parfait d\'un plat en sauce qui a mijoté doucement.', 23, 60.85, 'Gigondas', 'Syrah', 'public/uploads/64a9c9032f072-Maison_E_Guigal_2018.jpg', 'France', 2018, 1, 1),
(72, 'Maison E. Guigal 2016', 'La robe est rouge rubis. Le nez délivre dans un premier temps des notes forestières et de cacao. En second temps des notes de fruits apparaissent. En bouche, le Châteauneuf du Pape est corsé avec une texture velouté.', 10, 91.9, 'Châteauneuf du Pape', 'Grenache', 'public/uploads/64e3886001f13-Maison_E_Guigal_2016.jpg', 'France', 2016, 1, 1),
(73, 'Domaine Montrose 2020', 'Jolie robe rose pâle. Nez complexe d’agrumes mûrs, mandarines, épices. La bouche est élégante, tout en finesse.', 43, 19.35, 'Vin de Pays d\'Oc', 'Grenache, Rolle', 'public/uploads/64a9c655ce25c-Domaine_Montrose_2020.jpg', 'France', 2019, 2, 0),
(74, 'Domaine Auzias 2019', 'La Cité des Vents Rosé du Château Auzias est un vin rosé de la région de Cite de Carcassonne en Vin de Pays. En bouche ce vin rosé est un vin avec une belle fraîcheur. Ce vin s\'accorde généralement bien avec un plat végétarien, des apéritifs et snacks ou du poisson maigre.', 46, 14.7, 'IGP Cité de Carcassonne', 'Syrah, Cabernet Franc, Grenache', 'public/uploads/64a9c6d9a309b-Domaine_Auzias_Cite_des_Vents_2019.png', 'France', 2019, 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` smallint(2) UNSIGNED NOT NULL,
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'user'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `email` varchar(160) NOT NULL,
  `password` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `zipCode` varchar(15) NOT NULL,
  `country` varchar(50) NOT NULL,
  `roles_id` smallint(2) UNSIGNED NOT NULL DEFAULT '1',
  `blocked` varchar(10) NOT NULL DEFAULT 'false',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `birthday`, `email`, `password`, `address`, `city`, `zipCode`, `country`, `roles_id`, `blocked`, `created_at`) VALUES
(1, 'user1firstname', 'user2lastname', '2003-06-01', 'user1@exemple.com', 'password', 'adresse1', 'Paris', '75000', 'France', 1, 'false', '2023-06-13 10:39:56'),
(2, 'JULIEN', 'THOMAS', '1976-05-28', 'julien.thomas.mg@gmail.com', '$2y$10$SNGeyLzD8cQVJ2XvJ8CZ3OvD5XHfckWamNB5klguPN9H.FIhgjNSW', '9 BAILLS DEL JOC DE LA PILOTA', 'ARLES SUR TECH', '66150', 'France', 2, 'false', '2023-06-13 10:53:11'),
(3, 'JULIEN', 'THOMAS', '1976-05-28', 'julien_thomas9@yahoo.fr', '$2y$10$EYPn1RARqCqxa5825II4ruV5fJfJHkgzUskOSSFkVaCko35vXf18O', '9 BAILLS DEL JOC DE LA PILOTA', 'ARLES SUR TECH', '66150', 'France', 1, 'false', '2023-06-13 12:35:07'),
(5, 'toto', 'toto', '2000-05-28', 'toto@gmail.com', '$2y$10$onHH.NSPMrLzfiOYpxDvW.jyYyJbRGpulHegyoNCE52lC1o3d9Wte', '9 BAILLS DEL JOC DE LA PILOTA', 'ARLES SUR TECH', '66150', 'France', 1, 'false', '2023-06-16 16:31:55'),
(12, 'julien', 'thomas', '2000-01-01', 'admin@gmail.com', '$2y$10$vna4CT57hQow95YJl6B5P.dOUq6JSS7zp56pJvb1BM0hOb9u/NwCm', '8 rue du square', 'Paris', '75001', 'France', 2, 'false', '2023-06-20 08:57:19'),
(13, 'JULIEN', 'THOMAS', '2000-01-01', 'mapave1597@fitwl.com', '$2y$10$3EGWCjnTSMc.T7Mcs/rp5eUW8TCNWz9u0oQH8616sBYFrxUTIXJYm', '9 BAILLS DEL JOC DE LA PILOTA', 'ARLES SUR TECH', '66150', 'France', 1, 'false', '2023-06-29 08:53:50'),
(14, 'julien', 'Julien', '2000-05-28', 'juju@gmail.com', '$2y$10$hjbbA0zgGoi3VTwnp1QoQOnx4RTccg4X6vNw6QqefXqHYnLdYEtXq', 'd', 'gggggggggggggggggggg', 'ggggggggggg', 'ggggggggggggggggggggggggg', 1, 'false', '2023-06-30 21:47:42'),
(15, 'juju', 'thomas', '2000-05-28', 'mapave1597@fitwl.gmail', '$2y$10$7.osey.YiGqHnJ37NRrW1uAQ9nsAz7UqN/hIXjckZFqziI4YW/k8O', '8 RUE HEMINGWAY', 'MONTIGNY LE BRETONNEUX', '78180', 'France', 1, 'false', '2023-07-03 16:29:10'),
(16, 'test', 'test', '2000-01-28', 'test@gmail.com', '$2y$10$Iz9KnWWp0Vy1BesVWVcXtOnyDtUDDFS96cqtCCRydeyRxnKa7fSD.', 'test', 'test', '75002', 'test', 1, 'false', '2023-08-21 12:21:46');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `opinions`
--
ALTER TABLE `opinions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`users_id`),
  ADD KEY `product_id` (`products_id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`users_id`);

--
-- Index pour la table `orders_details`
--
ALTER TABLE `orders_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_id` (`orders_id`),
  ADD KEY `product_id` (`products_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_id` (`categories_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`roles_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` smallint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `opinions`
--
ALTER TABLE `opinions`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `orders_details`
--
ALTER TABLE `orders_details`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` smallint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `opinions`
--
ALTER TABLE `opinions`
  ADD CONSTRAINT `opinions_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `opinions_ibfk_2` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `orders_details`
--
ALTER TABLE `orders_details`
  ADD CONSTRAINT `orders_details_ibfk_1` FOREIGN KEY (`products_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `orders_details_ibfk_2` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
