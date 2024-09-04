-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  ven. 23 août 2024 à 14:06
-- Version du serveur :  10.3.39-MariaDB-0+deb10u2
-- Version de PHP :  7.3.31-1~deb10u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projets_exam-back_mdurand`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `ca_id` int(11) NOT NULL COMMENT 'Identifiant unique de la catégorie',
  `ca_libelle` varchar(100) NOT NULL COMMENT 'Libelle affichable de la catégorie',
  `ca_ref_piecejointe_image` int(11) DEFAULT NULL COMMENT 'Image illustrant la catégorie'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table des catégories de produit';

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`ca_id`, `ca_libelle`, `ca_ref_piecejointe_image`) VALUES
(1, 'menus', 79),
(2, 'boissons', 33),
(3, 'burgers', 23),
(4, 'frites', 42),
(5, 'encas', 44),
(6, 'salades', 65),
(7, 'desserts', 56),
(8, 'sauces', 63),
(9, 'wraps', 69);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `c_id` int(11) NOT NULL COMMENT 'Identifiant unique de la commande',
  `c_num_cde` varchar(10) NOT NULL COMMENT 'Numéro de commande',
  `c_ref_cde` varchar(10) NOT NULL COMMENT 'Référence de la commande',
  `c_type_service` char(3) NOT NULL COMMENT 'Service Sur Place ou à Table',
  `c_num_chevalet` int(3) DEFAULT NULL COMMENT 'Numéro du chevalet pour le service sur place',
  `c_commentaire` text DEFAULT NULL COMMENT 'Commentaire éventuel',
  `c_statut` char(3) NOT NULL DEFAULT 'AP' COMMENT 'Statut de la commande (C Créée,AP A Préparer,P Prête,L Livrée,S Supprimée)',
  `c_datetime_creation` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Date de la création de la commande',
  `c_datetime_modification` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Date de dernière modification de la commande',
  `c_datetime_suppression` datetime DEFAULT NULL COMMENT 'Date de suppression de la commande',
  `c_ref_user_preparation` int(11) DEFAULT NULL COMMENT 'Utilisateur ayant préparé la commande (passée en statut P Prête)',
  `c_datetime_preparation` datetime DEFAULT NULL COMMENT 'Date de la préparation de la commande',
  `c_ref_user_livraison` int(11) DEFAULT NULL COMMENT 'Utilisateur ayant livré la commande (passée en statut L Livrée)',
  `c_datetime_livraison` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Date (estimée ou réelle) de la livraison de la commande'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table des commandes';

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`c_id`, `c_num_cde`, `c_ref_cde`, `c_type_service`, `c_num_chevalet`, `c_commentaire`, `c_statut`, `c_datetime_creation`, `c_datetime_modification`, `c_datetime_suppression`, `c_ref_user_preparation`, `c_datetime_preparation`, `c_ref_user_livraison`, `c_datetime_livraison`) VALUES
(2, '69197', 'SP6600', 'SP', 123, 'TEST', 'L', '2024-08-20 14:29:12', '2024-08-22 11:55:08', NULL, NULL, '2024-08-22 11:53:32', NULL, '2024-08-22 11:55:08'),
(3, '15179', 'AE6682', 'AE', NULL, 'TEST 2', 'AP', '2024-08-20 20:46:32', '2024-08-23 11:22:06', NULL, NULL, NULL, NULL, '2024-08-23 11:27:06'),
(4, '80183', 'SP2576', 'SP', 123, 'TEST 3', 'AP', '2024-08-22 13:38:40', '2024-08-22 13:48:05', NULL, NULL, NULL, NULL, '2024-08-22 13:53:05'),
(5, '67596', 'AE8261', 'AE', NULL, 'TEST 5', 'AP', '2024-08-23 11:22:22', '2024-08-23 11:22:39', NULL, NULL, NULL, NULL, '2024-08-23 11:27:39'),
(6, '24378', 'AE1151', 'AE', NULL, 'TEST 6', 'C', '2024-08-23 11:23:46', '2024-08-23 11:23:46', NULL, NULL, NULL, NULL, '2024-08-23 11:28:46');

-- --------------------------------------------------------

--
-- Structure de la table `commande_produit`
--

CREATE TABLE `commande_produit` (
  `cp_id` int(11) NOT NULL COMMENT 'Identifiant unique de la table lien entre un produit et sa commande',
  `cp_quantite` int(3) NOT NULL COMMENT 'Quantité du produit commandée',
  `cp_ref_format` int(11) DEFAULT NULL COMMENT 'Référence au format du produit demandé',
  `cp_ref_produit_menu` int(11) DEFAULT NULL COMMENT 'Référence à l''identifiant du produit menu si le produit appartient à un menu',
  `cp_ref_menu_commande` int(11) DEFAULT NULL COMMENT 'Référence à l''identifiant du menu dans la commande',
  `cp_ref_commande` int(11) NOT NULL COMMENT 'Référence à l''identifiant de la commande',
  `cp_ref_produit` int(11) NOT NULL COMMENT 'Référence à l''identifiant du produit'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table faisant le lien entre la commande et les produits commandés';

--
-- Déchargement des données de la table `commande_produit`
--

INSERT INTO `commande_produit` (`cp_id`, `cp_quantite`, `cp_ref_format`, `cp_ref_produit_menu`, `cp_ref_menu_commande`, `cp_ref_commande`, `cp_ref_produit`) VALUES
(1, 1, NULL, 1, 4, 2, 35),
(2, 1, NULL, 1, 4, 2, 27),
(3, 1, NULL, 1, 4, 2, 53),
(4, 1, 1, NULL, NULL, 2, 1),
(5, 1, 3, NULL, NULL, 2, 33),
(6, 1, 1, NULL, NULL, 4, 1),
(7, 1, NULL, 1, 6, 4, 35),
(8, 1, NULL, 1, 6, 4, 27),
(9, 1, NULL, 1, 6, 4, 53),
(10, 1, 1, NULL, NULL, 4, 1),
(11, 1, NULL, 1, 10, 4, 35),
(12, 1, NULL, 1, 10, 4, 27),
(13, 1, NULL, 1, 10, 4, 53),
(14, 1, 11, NULL, NULL, 4, 52),
(15, 1, 1, NULL, NULL, 4, 1),
(16, 1, NULL, 1, 15, 4, 35),
(17, 1, NULL, 1, 15, 4, 27),
(18, 1, NULL, 1, 15, 4, 53),
(19, 1, 11, NULL, NULL, 4, 52),
(20, 1, 1, NULL, NULL, 3, 1),
(21, 1, NULL, 1, 20, 3, 35),
(22, 1, NULL, 1, 20, 3, 27),
(23, 2, 3, NULL, NULL, 3, 33),
(24, 1, 1, NULL, NULL, 3, 2),
(25, 1, NULL, 2, 24, 3, 35),
(26, 1, NULL, 2, 24, 3, 27),
(27, 1, NULL, 2, 24, 3, 53),
(28, 1, NULL, 2, 24, 3, 40),
(29, 1, 1, NULL, NULL, 3, 1),
(30, 1, NULL, 1, 29, 3, 35),
(31, 1, NULL, 1, 29, 3, 27),
(32, 1, 1, NULL, NULL, 5, 13),
(33, 1, NULL, 13, 32, 5, 35),
(34, 1, NULL, 13, 32, 5, 27),
(35, 1, NULL, 13, 32, 5, 64),
(36, 1, NULL, 13, 32, 5, 44),
(37, 1, 5, NULL, NULL, 5, 21);

-- --------------------------------------------------------

--
-- Structure de la table `format`
--

CREATE TABLE `format` (
  `f_id` int(11) NOT NULL COMMENT 'Identifiant unique de la table format',
  `f_libelle` varchar(100) NOT NULL COMMENT 'Libellé affichage du format',
  `f_format_standard` char(3) NOT NULL COMMENT 'Format standardisé',
  `f_format_prix` float(4,2) NOT NULL COMMENT 'Impact du format sur le prix du produit',
  `f_ref_categorie` int(11) NOT NULL COMMENT 'Référence à la catégorie sur laquelle le format peut s''appliqué'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table des formats possibles';

--
-- Déchargement des données de la table `format`
--

INSERT INTO `format` (`f_id`, `f_libelle`, `f_format_standard`, `f_format_prix`, `f_ref_categorie`) VALUES
(1, 'Best Of', 'N', 0.00, 1),
(2, 'Maxi Best Of', 'XL', 1.00, 1),
(3, '30cl', 'N', 0.00, 2),
(4, '50cl', 'XL', 0.50, 2),
(5, 'Normal', 'N', 0.00, 3),
(6, 'Petite', 'S', -0.50, 4),
(7, 'Moyenne', 'N', 0.00, 4),
(8, 'Grande', 'XL', 0.50, 4),
(9, 'Normal', 'N', 0.00, 5),
(10, 'Normal', 'N', 0.00, 6),
(11, 'Normal', 'N', 0.00, 7),
(12, 'Normal', 'N', 0.00, 8),
(13, 'Normal', 'N', 0.00, 9);

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `m_id` int(11) NOT NULL COMMENT 'Identifiant unique',
  `m_libelle` varchar(100) NOT NULL COMMENT 'Libellé affichable du choix dans le menu',
  `m_ref_produit_menu` int(11) NOT NULL COMMENT 'Référence au produit qui est le menu',
  `m_ref_categorie_possible` int(11) NOT NULL COMMENT 'Référence à la catégorie possible pour cette ligne du menu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table de composition des produits de catégorie menu';

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`m_id`, `m_libelle`, `m_ref_produit_menu`, `m_ref_categorie_possible`) VALUES
(16, 'Accompagnement', 2, 4),
(17, 'Boisson', 2, 2),
(18, 'Sauce', 2, 8),
(19, 'Supplément', 2, 5),
(23, 'Accompagnement', 4, 4),
(24, 'Boisson', 4, 2),
(25, 'Supplément', 4, 5),
(29, 'Accompagnement', 6, 4),
(30, 'Boisson', 6, 2),
(31, 'Accompagnement', 7, 4),
(32, 'Boisson', 7, 2),
(33, 'Supplément', 7, 5),
(34, 'Accompagnement', 8, 6),
(35, 'Boisson', 8, 2),
(36, 'Accompagnement', 9, 4),
(37, 'Boisson', 9, 2),
(38, 'Sauce', 9, 8),
(39, 'Accompagnement', 10, 4),
(40, 'Boisson', 10, 2),
(41, 'Accompagnement', 11, 4),
(42, 'Boisson', 11, 2),
(43, 'Sauce', 11, 8),
(44, 'Dessert', 11, 7),
(45, 'Supplément', 11, 5),
(46, 'Accompagnement', 12, 4),
(47, 'Boisson', 12, 2),
(48, 'Burger supplémentaire', 12, 3),
(49, 'Accompagnement', 13, 4),
(50, 'Boisson', 13, 2),
(51, 'Wrap au choix', 13, 9),
(52, 'Dessert', 13, 7),
(53, 'Accompagnement', 1, 4),
(54, 'Boisson', 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `piecejointe`
--

CREATE TABLE `piecejointe` (
  `pj_id` int(11) NOT NULL COMMENT 'Identifiant unique de la pièce jointe',
  `pj_nom_fichier` varchar(100) NOT NULL COMMENT 'Nom du fichier',
  `pj_taille` int(11) NOT NULL COMMENT 'Taille du fichier',
  `pj_type_fichier` varchar(30) NOT NULL COMMENT 'Type MIME du fichier',
  `pj_chemin` varchar(100) NOT NULL COMMENT 'Chemin complet du fichier',
  `pj_statut` char(3) NOT NULL COMMENT 'Statut du fichier',
  `pj_date_creation` datetime NOT NULL COMMENT 'Date d''insertion du fichier',
  `pj_date_modification` datetime DEFAULT NULL COMMENT 'Date de dernière modification du fichier',
  `pj_date_suppression` datetime DEFAULT NULL COMMENT 'Date de suppression du fichier'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table de gestion des fichiers';

--
-- Déchargement des données de la table `piecejointe`
--

INSERT INTO `piecejointe` (`pj_id`, `pj_nom_fichier`, `pj_taille`, `pj_type_fichier`, `pj_chemin`, `pj_statut`, `pj_date_creation`, `pj_date_modification`, `pj_date_suppression`) VALUES
(1, '66c34f5f930f4_ef79cd289b3159f3.png', 33619, 'image/png', 'public/uploads/2024/08/19/', 'V', '2024-08-19 15:57:51', NULL, NULL),
(2, '66c3504059e22_67d4d912c1236eda.png', 40946, 'image/png', 'public/uploads/2024/08/19/', 'V', '2024-08-19 16:01:36', NULL, NULL),
(3, '66c3507642ade_50a6134da518d739.png', 26719, 'image/png', 'public/uploads/2024/08/19/', 'V', '2024-08-19 16:02:30', NULL, NULL),
(4, '66c49e00c7aa9_731f24181321af70.png', 40628, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 15:45:36', NULL, NULL),
(5, '66c49e462eac8_0db6fbc5184380bf.png', 40628, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 15:46:46', NULL, NULL),
(6, '66c4a17e140ec_5467e3f7ceb751e3.png', 40628, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:00:30', NULL, NULL),
(7, '66c4a7b80c747_7a0ce4061ef641ff.png', 40628, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:27:04', NULL, NULL),
(8, '66c4a7d89670b_826bb09de1e159d1.png', 52368, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:27:36', NULL, NULL),
(9, '66c4a7e42cff9_4bd701f223845eb2.png', 49386, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:27:48', NULL, NULL),
(10, '66c4a7f1ab3a0_c67725b45d409c4c.png', 43414, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:28:01', NULL, NULL),
(11, '66c4a7fc98873_1a3ca220db1e6c5c.png', 43545, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:28:12', NULL, NULL),
(12, '66c4a80b268cc_5244a0b2c1976355.png', 34510, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:28:27', NULL, NULL),
(13, '66c4a8421d01b_eb06cc57b8593dd4.png', 33619, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:29:22', NULL, NULL),
(14, '66c4a85b7bd9b_bb7c0ed35626b959.png', 33343, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:29:47', NULL, NULL),
(15, '66c4a87b4aacd_25d4af722d98c9d2.png', 46630, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:30:19', NULL, NULL),
(16, '66c4a89161a71_d9bc67f5760b4ba4.png', 48873, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 16:30:41', NULL, NULL),
(17, '66c4e077c891a_3e3dbc8665e60ad7.png', 50600, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:29:11', NULL, NULL),
(18, '66c4e082ad2e7_8ae38907a55ca9dc.png', 49900, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:29:22', NULL, NULL),
(19, '66c4e08ad60e5_59f802a6f05ccc7a.png', 46791, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:29:30', NULL, NULL),
(20, '66c4e130ce22f_89b855fe08c46072.png', 40628, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:32:16', NULL, NULL),
(21, '66c4e1610ff82_58294f3a541683fc.png', 52368, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:33:05', NULL, NULL),
(22, '66c4e16d396b4_e8abcade440de32a.png', 49386, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:33:17', NULL, NULL),
(23, '66c4e1a295321_43970dce23ddbbb3.png', 43414, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:34:10', NULL, NULL),
(24, '66c4e1a9d75a1_94811ae54cbd4c3a.png', 43545, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:34:17', NULL, NULL),
(25, '66c4e1af11e86_a35c7ee34abf2f30.png', 34510, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:34:23', NULL, NULL),
(26, '66c4e1b535c35_2e24ac23ead03f37.png', 33619, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:34:29', NULL, NULL),
(27, '66c4e1bc1229e_8daaea31313dc6e3.png', 33343, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:34:36', NULL, NULL),
(28, '66c4e1c12bb9c_3c21bd56960cbd82.png', 46630, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:34:41', NULL, NULL),
(29, '66c4e1c6e72e5_d47f666fbd6d1a31.png', 48873, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:34:46', NULL, NULL),
(30, '66c4e1ccdd7e5_eacfd5ac793f7cd2.png', 50600, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:34:52', NULL, NULL),
(31, '66c4e1d233e72_2419efb096761732.png', 49900, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:34:58', NULL, NULL),
(32, '66c4e1d8295b7_14068c33776c0b68.png', 46791, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:35:04', NULL, NULL),
(33, '66c4e1fa989b9_89ea5ca609f8b17d.png', 17170, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:35:38', NULL, NULL),
(34, '66c4e1ffdba36_2ca0f4dbebe5410b.png', 16907, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:35:43', NULL, NULL),
(35, '66c4e2050b593_fead264506055ba8.png', 9853, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:35:49', NULL, NULL),
(36, '66c4e20a40d1a_1ffdf98ed9da4304.png', 16228, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:35:54', NULL, NULL),
(37, '66c4e20f5f7c4_8093603581808ae0.png', 17340, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:35:59', NULL, NULL),
(38, '66c4e214be479_9cf8ca8a4ea83c0c.png', 15175, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:36:04', NULL, NULL),
(39, '66c4e220ee29d_1cbbd7e2e493fc1f.png', 15175, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:36:16', NULL, NULL),
(40, '66c4e227da8ee_34eb3f15a0622cf4.png', 17911, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:36:23', NULL, NULL),
(41, '66c4e2328d5f3_4a867ad419045ef8.png', 23048, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:36:34', NULL, NULL),
(42, '66c4e26f3d495_b62fec872c894a66.png', 32078, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:37:35', NULL, NULL),
(43, '66c4e2786bc54_ae512912fe8a3c33.png', 19592, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:37:44', NULL, NULL),
(44, '66c4e2a8e2b43_b19c672d6bbff868.png', 26719, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:38:32', NULL, NULL),
(45, '66c4e2af3abfb_65f845e09751a499.png', 54873, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:38:39', NULL, NULL),
(46, '66c4e2b4a8888_83f0f33c27012e75.png', 32723, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:38:44', NULL, NULL),
(47, '66c4e2ba71abb_7af90f62b3cd28d2.png', 29333, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:38:50', NULL, NULL),
(48, '66c4e2d726aed_76b6fbfa760aa84e.png', 22780, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:39:19', NULL, NULL),
(49, '66c4e2dc96c20_9eba3a5924e85b67.png', 89261, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:39:24', NULL, NULL),
(50, '66c4e2e17acd6_4812d44a8567b170.png', 91362, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:39:29', NULL, NULL),
(51, '66c4e2e712f72_79272d66aa1caa8c.png', 22133, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:39:35', NULL, NULL),
(52, '66c4e2ec91c1e_d788165a13a33674.png', 22860, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:39:40', NULL, NULL),
(53, '66c4e2f2542c2_ff3b0ef04c8edad9.png', 26810, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:39:46', NULL, NULL),
(54, '66c4e2f7a3eb0_2498d4d119d3cdf7.png', 23438, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:39:51', NULL, NULL),
(55, '66c4e2fc9f334_dba02504fb5c8b00.png', 31400, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:39:56', NULL, NULL),
(56, '66c4e30421b71_5adcd289debddab5.png', 24745, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:40:04', NULL, NULL),
(57, '66c4e3238676d_a92efac4682c839d.png', 91362, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:40:35', NULL, NULL),
(58, '66c4e32879381_52c713d28e09cbd8.png', 82890, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:40:40', NULL, NULL),
(59, '66c4e32de97b3_c6e22fffb024d72f.png', 85442, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:40:45', NULL, NULL),
(60, '66c4e332cd513_456fb893aa9b35b0.png', 56141, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:40:50', NULL, NULL),
(61, '66c4e337dd298_2507abf569821238.png', 91526, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:40:55', NULL, NULL),
(62, '66c4e33e62408_16f6f0f0b1307615.png', 87950, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:41:02', NULL, NULL),
(63, '66c4e34409251_0d19119260814949.png', 58201, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:41:08', NULL, NULL),
(64, '66c4e36b75e56_d0377caba5e99476.png', 21656, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:41:47', NULL, NULL),
(65, '66c4e3ca85f77_57c84daa557aea0c.png', 30498, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:43:22', NULL, NULL),
(66, '66c4e3dd7dd47_a23659d443911b6c.png', 21502, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:43:41', NULL, NULL),
(67, '66c4e434bdf5c_afb27799da17764f.png', 20969, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:45:08', NULL, NULL),
(68, '66c4e43a84ac9_ed88520212a075c8.png', 21295, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:45:14', NULL, NULL),
(69, '66c4e4400fc03_6c4eecde1171ca9b.png', 66004, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:45:20', NULL, NULL),
(70, '66c4e473246ea_3e5e82c49d4a266d.png', 58945, 'image/png', 'public/uploads/2024/08/20/', 'V', '2024-08-20 20:46:11', NULL, NULL),
(71, '66c73fe984862_806c416f4c7a211f.png', 37059, 'image/png', 'public/uploads/2024/08/22/', 'V', '2024-08-22 15:40:57', NULL, NULL),
(72, '66c74041ca9bd_f4031543eb82a887.png', 37059, 'image/png', 'public/uploads/2024/08/22/', 'V', '2024-08-22 15:42:25', NULL, NULL),
(73, '66c7426c327c1_2a0b86b6150377d9.png', 37059, 'image/png', 'public/uploads/2024/08/22/', 'V', '2024-08-22 15:51:40', NULL, NULL),
(74, '66c742a0bd371_2c58438de8a318ce.png', 19592, 'image/png', 'public/uploads/2024/08/22/', 'V', '2024-08-22 15:52:32', NULL, NULL),
(75, '66c7431175883_932f7a32c454336b.png', 19592, 'image/png', 'public/uploads/2024/08/22/', 'V', '2024-08-22 15:54:25', NULL, NULL),
(76, '66c74379a09a2_7525b8c1c614aea5.png', 37059, 'image/png', 'public/uploads/2024/08/22/', 'V', '2024-08-22 15:56:09', NULL, NULL),
(77, '66c7441f93468_3dbdcaa6a2747683.png', 37059, 'image/png', 'public/uploads/2024/08/22/', 'V', '2024-08-22 15:58:55', NULL, NULL),
(78, '66c875bf44e09_b0b46c3caee69cc6.png', 33619, 'image/png', 'public/uploads/2024/08/23/', 'V', '2024-08-23 13:42:55', NULL, NULL),
(79, '66c875c96bdad_193bbc5f057e3179.png', 58945, 'image/png', 'public/uploads/2024/08/23/', 'V', '2024-08-23 13:43:05', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `p_id` int(11) NOT NULL COMMENT 'Identifiant unique du produit',
  `p_libelle` varchar(100) NOT NULL COMMENT 'Libellé affichable du produit',
  `p_prix` decimal(7,2) NOT NULL COMMENT 'Prix du produit',
  `p_description` text NOT NULL COMMENT 'Description du produit',
  `p_isdispo` tinyint(1) NOT NULL COMMENT 'Indique si le produit est disponible ou non',
  `p_stock` int(11) NOT NULL COMMENT 'Stock en cours du produit',
  `p_ref_piecejointe_image` int(11) DEFAULT NULL COMMENT 'Référence à la pièce jointe image du produit',
  `p_ref_categorie` int(11) NOT NULL COMMENT 'Référence à la catégorie à laquelle appartient le produit'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table des produits';

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`p_id`, `p_libelle`, `p_prix`, `p_description`, `p_isdispo`, `p_stock`, `p_ref_piecejointe_image`, `p_ref_categorie`) VALUES
(1, 'Menu Le 280', '8.80', 'Menu Le 280', 1, 77, 7, 1),
(2, 'Menu Big Tasty', '10.60', 'Menu Big Tasty', 1, 95, 8, 1),
(4, 'Menu Big Mac', '8.00', 'Menu Big Mac', 1, 100, 10, 1),
(6, 'Menu MC Chicken', '9.30', 'Menu MC Chicken', 1, 100, 12, 1),
(7, 'Menu MC Crispy', '7.20', 'Menu MC Crispy', 1, 100, 13, 1),
(8, 'Menu MC Fish', '7.20', 'Menu MC Fish', 1, 100, 14, 1),
(9, 'Menu Royal Bacon', '7.05', 'Menu Royal Bacon', 1, 100, 15, 1),
(10, 'Menu Royal Cheese', '6.40', 'Menu Royal Cheese', 1, 100, 16, 1),
(11, 'Menu Royal Deluxe', '7.40', 'Menu Royal Deluxe', 1, 100, 17, 1),
(12, 'Menu Signature BBQ Beef 2 viandes', '13.50', 'Menu Signature BBQ Beef 2 viandes', 1, 100, 18, 1),
(13, 'Menu Signature Beef BBQ', '11.90', 'Menu Signature Beef BBQ', 1, 95, 19, 1),
(14, 'Le 280', '6.80', 'Le 280', 1, 100, 20, 3),
(15, 'Big Tasty', '8.60', 'Big Tasty', 1, 100, 21, 3),
(16, 'Big Tasty Bacon', '8.90', 'Big Tasty Bacon', 1, 100, 22, 3),
(17, 'Big Mac', '6.00', 'Big Mac', 1, 100, 23, 3),
(18, 'CBO', '8.90', 'CBO', 1, 100, 24, 3),
(19, 'MC Chicken', '7.30', 'MC Chicken', 1, 100, 25, 3),
(20, 'MC Crispy', '5.30', 'MC Crispy', 1, 100, 26, 3),
(21, 'MC Fish', '4.85', 'MC Fish', 1, 99, 27, 3),
(22, 'Royal Bacon', '5.10', 'Royal Bacon', 1, 100, 28, 3),
(23, 'Royal Cheese', '4.40', 'Royal Cheese', 1, 100, 29, 3),
(24, 'Royal Deluxe', '5.40', 'Royal Deluxe', 1, 100, 30, 3),
(25, 'Signature BBQ Beef 2 viandes', '11.40', 'Signature BBQ Beef 2 viandes', 1, 100, 31, 3),
(26, 'Signature Beef BBQ', '10.30', 'Signature Beef BBQ', 1, 100, 32, 3),
(27, 'Coca Cola', '1.90', 'Coca Cola', 1, 100, 33, 2),
(28, 'Coca Sans Sucres', '1.90', 'Coca Sans Sucres', 1, 100, 34, 2),
(29, 'Eau', '1.00', 'Eau', 1, 100, 35, 2),
(30, 'Fanta Orange', '1.90', 'Fanta Orange', 1, 100, 36, 2),
(31, 'Ice Tea Pêche', '1.90', 'Ice Tea Pêche', 1, 100, 37, 2),
(32, 'Ice Tea Citron', '1.90', 'Ice Tea Citron', 1, 100, 40, 2),
(33, 'Jus d\'Orange', '2.10', 'Jus d\'Orange', 1, 97, 39, 2),
(34, 'Jus de Pommes Bio', '2.30', 'Jus de Pommes Bio', 1, 100, 41, 2),
(35, 'Frites', '1.45', 'Frites', 1, 100, 42, 4),
(38, 'Potatoes', '2.15', 'Potatoes', 1, 100, 43, 4),
(40, 'Cheeseburger', '2.60', 'Cheeseburger', 1, 100, 44, 5),
(41, 'Croc MCdo', '3.20', 'Croc MCdo', 1, 100, 45, 5),
(42, 'Nuggets x4', '4.20', 'Nuggets x4', 1, 100, 46, 5),
(43, 'Nuggets x20', '13.00', 'Nuggets x20', 1, 100, 47, 5),
(44, 'Brownie', '2.60', 'Brownie', 1, 100, 48, 7),
(45, 'Cheesecake chocolat M&M\'S', '3.10', 'Cheesecake chocolat M&M\'S', 1, 100, 49, 7),
(46, 'Cheesecake Fraise', '3.10', 'Cheesecake Fraise', 1, 100, 50, 7),
(47, 'Cookie', '3.20', 'Cookie', 1, 100, 51, 7),
(48, 'Donut', '2.60', 'Donut', 1, 100, 52, 7),
(49, 'Macarons', '2.70', 'Macarons', 1, 100, 53, 7),
(50, 'MC Fleury', '4.40', 'MC Fleury', 1, 100, 54, 7),
(51, 'Muffin', '3.60', 'Muffin', 1, 100, 55, 7),
(52, 'Sunday', '1.00', 'Sunday', 1, 98, 56, 7),
(53, 'Classic Barbecue', '0.70', 'Classic Barbecue', 1, 100, 57, 8),
(54, 'Classic Moutarde', '0.70', 'Classic Moutarde', 1, 100, 58, 8),
(55, 'Creamy Deluxe', '0.70', 'Creamy Deluxe', 1, 100, 59, 8),
(56, 'Ketchup', '0.70', 'Ketchup', 1, 100, 60, 8),
(57, 'Chinoise', '0.70', 'Chinoise', 1, 100, 61, 8),
(58, 'Curry', '0.70', 'Curry', 1, 100, 62, 8),
(59, 'Pommes Frites', '0.70', 'Pommes Frites', 1, 100, 63, 8),
(60, 'Cesar Classic', '5.60', 'Salade Cesar Classic', 1, 100, 64, 6),
(61, 'Petite salade', '3.30', 'Petite salade', 1, 100, 65, 6),
(62, 'Italienne Mozza', '4.90', 'Italienne Mozza', 1, 100, 66, 6),
(64, 'Petit Wrap Ranch', '3.50', 'Petit Wrap Ranch', 1, 100, 67, 9),
(65, 'Petit Wrap Chèvre', '3.50', 'Petit Wrap Chèvre', 1, 100, 68, 9),
(66, 'MC Wrap Poulet Bacon', '5.90', 'MC Wrap Poulet Bacon', 1, 100, 69, 9),
(67, 'MC Wrap chevre', '5.90', 'MC Wrap chevre', 1, 100, 79, 9);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `u_id` int(11) NOT NULL COMMENT 'Identifiant unique de l''utilisateur',
  `u_nom` varchar(255) NOT NULL COMMENT 'Nom de l''utilisateur',
  `u_prenom` varchar(255) NOT NULL COMMENT 'Prénom de l''utilisateur',
  `u_role_user` char(30) NOT NULL COMMENT 'Rôle autorisé dans l''application',
  `u_email` varchar(320) NOT NULL COMMENT 'Adresse e-mail de l''utilisateur',
  `u_password` varchar(128) NOT NULL COMMENT 'Mot de passe hasher',
  `u_statut` char(3) NOT NULL COMMENT 'Statut de l''utilisateur (A Actif, D Désactivé)',
  `u_selector_reini_password` varchar(16) DEFAULT NULL COMMENT 'Token de sélection pour la réinitialisation',
  `u_token_reini_password` varchar(100) DEFAULT NULL COMMENT 'Token de réinitialisation du mot de passe',
  `u_expiration_reini_password` datetime DEFAULT NULL COMMENT 'Date d''expiration du  token'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Table des utilisateurs';

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`u_id`, `u_nom`, `u_prenom`, `u_role_user`, `u_email`, `u_password`, `u_statut`, `u_selector_reini_password`, `u_token_reini_password`, `u_expiration_reini_password`) VALUES
(1, 'Durand', 'Mickaël', 'ADMIN', 'mickael.durand@example.com', '$2y$10$8Z2IBoxsIz/.WRJU3349YugIYMSsc1udK/askkbrKTRcg5qCzAkZS', 'A', NULL, NULL, NULL),
(2, 'Staron', 'Blandine', 'SUPERVISEUR', 'blandine.staron@example.com', '$2y$10$BlVTJDKb37ePSSkmaed6X.Q872MT0XNfF6QpbcJDc/AGb/D3z8xg.', 'A', NULL, NULL, NULL),
(3, 'Lucao', 'Baptista', 'ACCUEIL', 'baptista.lucao@example.com', '$2y$10$BlVTJDKb37ePSSkmaed6X.Q872MT0XNfF6QpbcJDc/AGb/D3z8xg.', 'A', NULL, NULL, NULL),
(4, 'Delaistre', 'Corentin', 'PREPARATION', 'corentin.delaistre@example.com', '$2y$10$BlVTJDKb37ePSSkmaed6X.Q872MT0XNfF6QpbcJDc/AGb/D3z8xg.', 'A', NULL, NULL, NULL),
(5, 'Da Costa', 'Charles', 'PREPARATION', 'charles.dacosta@example.com', '$2y$10$0FcN7GtPOpiwwrAXOr8duuK//A8yMmkfggZtOsfnIkq4U/NoF8ba6', 'A', 'af384269a15eae03', '3448325b397783a1b69a67ae7229300f61150ecc5f5c9645effc63b341436e44', '2024-08-22 11:18:47'),
(6, 'Laugier', 'Antoine', 'ACCUEIL', 'antoine.laugier@example.com', '$2y$10$c5cYbgsU9mDwZFRQGPTaTeKFyqi670Eeeql/eDeM/Z5mOjhBF4vpq', 'A', '587b29f15d131962', '0f584528080684e11c97ea127c06c3738c8994c1e7bcf87c79fccb1291af6e12', '2024-08-22 11:27:42'),
(7, 'Durand', 'Mickaël', 'ADMIN', 'mickael.durand2@example.com', '$2y$10$n41fl3RGt5IM4x9LN4LQ1.tX.sI4rjBbbwVoMZ3IswxnCJs8Os1ma', 'A', '2f62f1edad45046a', '2ee9e301a57edb23879074973255b65125b4608fb3a3d4cf397c6d3d86e9bfde', '2024-08-23 12:55:25');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`ca_id`),
  ADD KEY `fk_piecejointe_categorie` (`ca_ref_piecejointe_image`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `c_ref_user_preparation` (`c_ref_user_preparation`),
  ADD KEY `c_ref_user_livraison` (`c_ref_user_livraison`);

--
-- Index pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD PRIMARY KEY (`cp_id`),
  ADD KEY `fk_produit_menu` (`cp_ref_produit_menu`),
  ADD KEY `fk_ref_commande` (`cp_ref_commande`) USING BTREE,
  ADD KEY `fk_ref_produit` (`cp_ref_produit`) USING BTREE,
  ADD KEY `fk_ref_format` (`cp_ref_format`),
  ADD KEY `fk_ref_menu_commande` (`cp_ref_menu_commande`);

--
-- Index pour la table `format`
--
ALTER TABLE `format`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `f_ref_categorie` (`f_ref_categorie`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`m_id`),
  ADD KEY `fk_ref_produit_menu` (`m_ref_produit_menu`) USING BTREE,
  ADD KEY `fk_ref_categorie_possible` (`m_ref_categorie_possible`) USING BTREE;

--
-- Index pour la table `piecejointe`
--
ALTER TABLE `piecejointe`
  ADD PRIMARY KEY (`pj_id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `p_ref_piecejointe_image` (`p_ref_piecejointe_image`),
  ADD KEY `p_ref_categorie` (`p_ref_categorie`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `ca_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant unique de la catégorie', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant unique de la commande', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  MODIFY `cp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant unique de la table lien entre un produit et sa commande', AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `format`
--
ALTER TABLE `format`
  MODIFY `f_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant unique de la table format', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant unique', AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `piecejointe`
--
ALTER TABLE `piecejointe`
  MODIFY `pj_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant unique de la pièce jointe', AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant unique du produit', AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identifiant unique de l''utilisateur', AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD CONSTRAINT `fk_piecejointe_categorie` FOREIGN KEY (`ca_ref_piecejointe_image`) REFERENCES `piecejointe` (`pj_id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`c_ref_user_preparation`) REFERENCES `utilisateur` (`u_id`),
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`c_ref_user_livraison`) REFERENCES `utilisateur` (`u_id`);

--
-- Contraintes pour la table `commande_produit`
--
ALTER TABLE `commande_produit`
  ADD CONSTRAINT `commande_produit_ibfk_1` FOREIGN KEY (`cp_ref_commande`) REFERENCES `commande` (`c_id`),
  ADD CONSTRAINT `commande_produit_ibfk_2` FOREIGN KEY (`cp_ref_produit`) REFERENCES `produit` (`p_id`),
  ADD CONSTRAINT `fk_produit_menu` FOREIGN KEY (`cp_ref_produit_menu`) REFERENCES `produit` (`p_id`),
  ADD CONSTRAINT `fk_ref_format` FOREIGN KEY (`cp_ref_format`) REFERENCES `format` (`f_id`),
  ADD CONSTRAINT `fk_ref_menu_commande` FOREIGN KEY (`cp_ref_menu_commande`) REFERENCES `commande_produit` (`cp_id`);

--
-- Contraintes pour la table `format`
--
ALTER TABLE `format`
  ADD CONSTRAINT `format_ibfk_1` FOREIGN KEY (`f_ref_categorie`) REFERENCES `categorie` (`ca_id`);

--
-- Contraintes pour la table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`m_ref_produit_menu`) REFERENCES `produit` (`p_id`),
  ADD CONSTRAINT `menu_ibfk_2` FOREIGN KEY (`m_ref_categorie_possible`) REFERENCES `categorie` (`ca_id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`p_ref_piecejointe_image`) REFERENCES `piecejointe` (`pj_id`),
  ADD CONSTRAINT `produit_ibfk_2` FOREIGN KEY (`p_ref_categorie`) REFERENCES `categorie` (`ca_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
