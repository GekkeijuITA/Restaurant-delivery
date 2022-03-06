-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 28, 2022 alle 23:14
-- Versione del server: 10.4.21-MariaDB
-- Versione PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dapin`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE `admin` (
  `email` varchar(255) NOT NULL,
  `psw` varchar(255) NOT NULL,
  `nome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `admin`
--

INSERT INTO `admin` (`email`, `psw`, `nome`) VALUES
('admin@admin.com', 'dapinadmin', 'Admin');

-- --------------------------------------------------------

--
-- Struttura della tabella `cartacredito`
--

CREATE TABLE `cartacredito` (
  `nCarta` int(11) NOT NULL,
  `dataScadenza` date DEFAULT NULL,
  `circuito` varchar(255) DEFAULT NULL,
  `proprietario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `catering`
--

CREATE TABLE `catering` (
  `ID` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `descrizione` varchar(255) DEFAULT NULL,
  `prezzo` int(11) DEFAULT NULL,
  `immagine` varchar(255) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `catering`
--

INSERT INTO `catering` (`ID`, `tipo`, `descrizione`, `prezzo`, `immagine`, `nome`) VALUES
(14, 'carne', 'Catering a base di carne di tutti i tipi(salsiccia, bistecca, braciola...)', 25, 'img/piatti/ilcavernicolo.jpg', 'Il cavernicolo'),
(15, 'Pesce', 'Catering a base di pesce(salmone, trota, crostacei...)', 25, 'img/piatti/ilbaciccia.jpg', 'Il baciccia');

-- --------------------------------------------------------

--
-- Struttura della tabella `cliente`
--

CREATE TABLE `cliente` (
  `email` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `nTelefono` varchar(10) DEFAULT NULL,
  `psw` varchar(255) NOT NULL,
  `immagine` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `indirizzo`
--

CREATE TABLE `indirizzo` (
  `ID` int(10) UNSIGNED NOT NULL,
  `via` varchar(255) NOT NULL,
  `civico` int(11) NOT NULL,
  `cap` int(11) NOT NULL,
  `interno` int(11) NOT NULL,
  `utente` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

CREATE TABLE `ordine` (
  `ID` int(11) UNSIGNED NOT NULL,
  `prenotazione` datetime NOT NULL,
  `cliente` varchar(255) DEFAULT NULL,
  `rider` varchar(255) DEFAULT NULL,
  `catering` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `quantita` int(11) NOT NULL,
  `codice` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `piatto`
--

CREATE TABLE `piatto` (
  `ID` int(6) UNSIGNED NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `descrizione` varchar(255) DEFAULT NULL,
  `prezzo` int(11) DEFAULT NULL,
  `immagine` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `piatto`
--

INSERT INTO `piatto` (`ID`, `nome`, `tipo`, `descrizione`, `prezzo`, `immagine`) VALUES
(8, 'Trofie al pesto', 'Primo', 'Trofie al pesto con patate e fagiolini', 10, 'img/piatti/trofiealpesto.jpg'),
(9, 'Pansoti al sugo di noci', 'Primo', 'Pansoti con una salsa di noci', 10, 'img/piatti/pansotialsugodinoci.jpg'),
(12, 'Fritto misto', 'Secondo', 'Fritto misto alla genovese', 10, 'img/piatti/frittomisto.jpg'),
(13, 'Americana', 'Pizza', 'Pizza con pomodoro, mozzarella, wustel e patatine fritte', 8, 'img/piatti/americana.jpg'),
(18, 'Panisse', 'Antipasto', 'Bocconcini di polenta fritti', 4, 'img/piatti/panisse.jpg'),
(19, 'Frisceu', 'Antipasto', 'Frittelle di pastella liguri', 4, 'img/piatti/frisceu.jpg'),
(20, 'Cuculli', 'Antipasto', 'Fritelle di pastella liguri con farina di ceci', 4, 'img/piatti/cuculli.jpg'),
(21, 'Torta pasqualina', 'Antipasto', 'Torta salata ripiena di spinaci, ricotta e uova', 4, 'img/piatti/tortapasqualina.jpg'),
(22, 'Ravioli al ragù', 'Primo', 'Ravioli di carne con il ragù', 8, 'img/piatti/raviolialragù.jpg'),
(23, 'Minestrone', 'Primo', 'Minestrone di verdure alla genovese', 8, 'img/piatti/minestrone.jpg'),
(24, 'Ciuppin', 'Primo', 'Zuppa di pesce alla genovese', 8, 'img/piatti/ciuppin.jpg'),
(25, 'Coniglio alla ligure', 'Secondo', 'Coniglio servito con sugo, olive e pinoli ', 14, 'img/piatti/coniglioallaligure.jpg'),
(26, 'Cima', 'Secondo', 'Cima ripiena di uova, vitello, piselli e parmigiano ', 12, 'img/piatti/cima.jpg'),
(27, 'Stoccafisso accomodato', 'Secondo', 'Stoccafisso alla ligure', 14, 'img/piatti/stoccafissoaccomodato.jpg'),
(28, 'Margherita', 'Pizza', 'Pomodoro, mozzarella, basilico', 6, 'img/piatti/margherita.jpeg'),
(29, 'Marinara', 'Pizza', 'Pomodoro, aglio, olio, acciughe e origano', 5, 'img/piatti/marinara.jpg'),
(30, 'Focaccia di Recco', 'Pizza', 'Focaccia al formaggio', 8, 'img/piatti/focacciadirecco.jpg'),
(31, 'Farinata', 'Pizza', 'Farinata di ceci', 7, 'img/piatti/farinata.jpg'),
(32, 'Meringata', 'Dolce', 'Torta con panna montata e meringhe', 4, 'img/piatti/meringata.jpg'),
(33, 'Sacripantina', 'Dolce', 'Torta con pan di spagna, crema al burro, crema al cacao e marsala', 4, 'img/piatti/sacripantina.jpg'),
(34, 'Pandolce genovese', 'Dolce', 'Pandolce con uvetta, canditi e pinoli', 5, 'img/piatti/pandolcegenovese.jpg'),
(35, 'Gelato', 'Dolce', 'Gelato crema e cioccolato', 4, 'img/piatti/gelato.jpg'),
(36, 'Coca cola', 'Bevanda', 'Bibita gasata', 3, 'img/piatti/cocacola.jpg'),
(37, 'Fanta', 'Bevanda', 'Bibita gasata', 3, 'img/piatti/fanta.jpg'),
(38, 'Sprite', 'Bevanda', 'Bibita gasata', 3, 'img/piatti/sprite.jpg'),
(39, 'Val Polcevera', 'Bevanda', 'Vino bianco liscio', 12, 'img/piatti/valpolcevera.jpg'),
(40, 'Riviera Ligure di Ponente', 'Bevanda', 'Vino bianco liscio', 18, 'img/piatti/rivieraligurediponente.jpg'),
(41, 'Birra Busalla', 'Bevanda', 'Birra ambrata Castagnasca', 11, 'img/piatti/birrabusalla.jpg'),
(42, 'Chinotto', 'Bevanda', 'Spuma', 5, 'img/piatti/chinotto.jpg'),
(43, 'Corochinato', 'Bevanda', 'Vino bianco aromatizzato', 12, 'img/piatti/corochinato.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `rider`
--

CREATE TABLE `rider` (
  `ID` int(11) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `consegne` int(11) DEFAULT NULL,
  `stipendio` int(11) DEFAULT NULL,
  `libero` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `rider`
--

INSERT INTO `rider` (`ID`, `nome`, `cognome`, `email`, `consegne`, `stipendio`, `libero`) VALUES
(11, 'rider', '1', 'rider1@rider.com', 0, 0, 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Indici per le tabelle `cartacredito`
--
ALTER TABLE `cartacredito`
  ADD PRIMARY KEY (`nCarta`);

--
-- Indici per le tabelle `catering`
--
ALTER TABLE `catering`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `nTelefono` (`nTelefono`);

--
-- Indici per le tabelle `indirizzo`
--
ALTER TABLE `indirizzo`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `piatto`
--
ALTER TABLE `piatto`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `rider`
--
ALTER TABLE `rider`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `catering`
--
ALTER TABLE `catering`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `indirizzo`
--
ALTER TABLE `indirizzo`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT per la tabella `piatto`
--
ALTER TABLE `piatto`
  MODIFY `ID` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT per la tabella `rider`
--
ALTER TABLE `rider`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
