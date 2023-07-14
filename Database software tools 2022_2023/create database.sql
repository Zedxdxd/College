USE [master]
GO
/****** Object:  Database [prodavnice]    Script Date: 20/06/2023 00:55:22 ******/
CREATE DATABASE [prodavnice]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'prodavnice', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL15.MSSQLSERVER\MSSQL\DATA\prodavnice.mdf' , SIZE = 8192KB , MAXSIZE = UNLIMITED, FILEGROWTH = 65536KB )
 LOG ON 
( NAME = N'prodavnice_log', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL15.MSSQLSERVER\MSSQL\DATA\prodavnice_log.ldf' , SIZE = 73728KB , MAXSIZE = 2048GB , FILEGROWTH = 65536KB )
 WITH CATALOG_COLLATION = DATABASE_DEFAULT
GO
ALTER DATABASE [prodavnice] SET COMPATIBILITY_LEVEL = 150
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [prodavnice].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [prodavnice] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [prodavnice] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [prodavnice] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [prodavnice] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [prodavnice] SET ARITHABORT OFF 
GO
ALTER DATABASE [prodavnice] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [prodavnice] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [prodavnice] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [prodavnice] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [prodavnice] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [prodavnice] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [prodavnice] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [prodavnice] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [prodavnice] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [prodavnice] SET  DISABLE_BROKER 
GO
ALTER DATABASE [prodavnice] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [prodavnice] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [prodavnice] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [prodavnice] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [prodavnice] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [prodavnice] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [prodavnice] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [prodavnice] SET RECOVERY FULL 
GO
ALTER DATABASE [prodavnice] SET  MULTI_USER 
GO
ALTER DATABASE [prodavnice] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [prodavnice] SET DB_CHAINING OFF 
GO
ALTER DATABASE [prodavnice] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [prodavnice] SET TARGET_RECOVERY_TIME = 60 SECONDS 
GO
ALTER DATABASE [prodavnice] SET DELAYED_DURABILITY = DISABLED 
GO
ALTER DATABASE [prodavnice] SET ACCELERATED_DATABASE_RECOVERY = OFF  
GO
EXEC sys.sp_db_vardecimal_storage_format N'prodavnice', N'ON'
GO
ALTER DATABASE [prodavnice] SET QUERY_STORE = OFF
GO
USE [prodavnice]
GO
/****** Object:  Table [dbo].[Artikal]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Artikal](
	[IdArt] [int] IDENTITY(1,1) NOT NULL,
	[Cena] [decimal](10, 3) NULL,
	[Naziv] [varchar](100) NULL,
 CONSTRAINT [XPKArtikal] PRIMARY KEY CLUSTERED 
(
	[IdArt] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Grad]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Grad](
	[IdGra] [int] IDENTITY(1,1) NOT NULL,
	[Ime] [varchar](100) NULL,
 CONSTRAINT [XPKGrad] PRIMARY KEY CLUSTERED 
(
	[IdGra] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Kupac]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Kupac](
	[IdKup] [int] IDENTITY(1,1) NOT NULL,
	[IdGra] [int] NULL,
	[Naziv] [varchar](100) NULL,
	[Novac] [decimal](10, 3) NULL,
 CONSTRAINT [XPKKupac] PRIMARY KEY CLUSTERED 
(
	[IdKup] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Linija]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Linija](
	[IdGra1] [int] NOT NULL,
	[IdGra2] [int] NOT NULL,
	[Distanca] [int] NULL,
	[IdLin] [int] IDENTITY(1,1) NOT NULL,
 CONSTRAINT [XPKLinija] PRIMARY KEY CLUSTERED 
(
	[IdLin] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Oglasava]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Oglasava](
	[Kolicina] [char](18) NULL,
	[IdArt] [int] NOT NULL,
	[IdPro] [int] NOT NULL,
 CONSTRAINT [XPKOglasava] PRIMARY KEY CLUSTERED 
(
	[IdArt] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Porudzbina]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Porudzbina](
	[IdPor] [int] IDENTITY(1,1) NOT NULL,
	[IdKup] [int] NULL,
	[Stanje] [varchar](100) NULL,
	[VremeSlanja] [datetime] NULL,
	[VremePrijema] [datetime] NULL,
	[IdGra] [int] NULL,
 CONSTRAINT [XPKPorudzbina] PRIMARY KEY CLUSTERED 
(
	[IdPor] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Prodavnica]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Prodavnica](
	[IdPro] [int] IDENTITY(1,1) NOT NULL,
	[IdGra] [int] NULL,
	[Ime] [varchar](100) NULL,
	[Popust] [decimal](10, 3) NULL,
 CONSTRAINT [XPKProdavnica] PRIMARY KEY CLUSTERED 
(
	[IdPro] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Sadrzi]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Sadrzi](
	[Kolicina] [int] NULL,
	[IdPor] [int] NOT NULL,
	[IdArt] [int] NOT NULL,
	[IdGra] [int] NULL,
	[IdSad] [int] IDENTITY(1,1) NOT NULL,
	[BrDana] [int] NULL,
 CONSTRAINT [XPKSadrzi] PRIMARY KEY CLUSTERED 
(
	[IdSad] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Sistem]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Sistem](
	[Profit] [decimal](10, 3) NULL
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[Transakcija]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Transakcija](
	[IdTra] [int] IDENTITY(1,1) NOT NULL,
	[Novac] [decimal](10, 3) NULL,
	[Datum] [datetime] NULL,
	[IdPor] [int] NULL,
	[IdPro] [int] NULL,
	[IdKup] [int] NULL,
 CONSTRAINT [XPKTransakcija] PRIMARY KEY CLUSTERED 
(
	[IdTra] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
ALTER TABLE [dbo].[Kupac]  WITH CHECK ADD  CONSTRAINT [R_12] FOREIGN KEY([IdGra])
REFERENCES [dbo].[Grad] ([IdGra])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[Kupac] CHECK CONSTRAINT [R_12]
GO
ALTER TABLE [dbo].[Linija]  WITH CHECK ADD  CONSTRAINT [R_10] FOREIGN KEY([IdGra1])
REFERENCES [dbo].[Grad] ([IdGra])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[Linija] CHECK CONSTRAINT [R_10]
GO
ALTER TABLE [dbo].[Oglasava]  WITH CHECK ADD  CONSTRAINT [R_3] FOREIGN KEY([IdArt])
REFERENCES [dbo].[Artikal] ([IdArt])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[Oglasava] CHECK CONSTRAINT [R_3]
GO
ALTER TABLE [dbo].[Oglasava]  WITH CHECK ADD  CONSTRAINT [R_4] FOREIGN KEY([IdPro])
REFERENCES [dbo].[Prodavnica] ([IdPro])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[Oglasava] CHECK CONSTRAINT [R_4]
GO
ALTER TABLE [dbo].[Porudzbina]  WITH CHECK ADD  CONSTRAINT [R_35] FOREIGN KEY([IdGra])
REFERENCES [dbo].[Grad] ([IdGra])
GO
ALTER TABLE [dbo].[Porudzbina] CHECK CONSTRAINT [R_35]
GO
ALTER TABLE [dbo].[Porudzbina]  WITH CHECK ADD  CONSTRAINT [R_7] FOREIGN KEY([IdKup])
REFERENCES [dbo].[Kupac] ([IdKup])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[Porudzbina] CHECK CONSTRAINT [R_7]
GO
ALTER TABLE [dbo].[Prodavnica]  WITH CHECK ADD  CONSTRAINT [R_1] FOREIGN KEY([IdGra])
REFERENCES [dbo].[Grad] ([IdGra])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[Prodavnica] CHECK CONSTRAINT [R_1]
GO
ALTER TABLE [dbo].[Sadrzi]  WITH CHECK ADD  CONSTRAINT [R_8] FOREIGN KEY([IdPor])
REFERENCES [dbo].[Porudzbina] ([IdPor])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[Sadrzi] CHECK CONSTRAINT [R_8]
GO
ALTER TABLE [dbo].[Sadrzi]  WITH CHECK ADD  CONSTRAINT [R_9] FOREIGN KEY([IdArt])
REFERENCES [dbo].[Artikal] ([IdArt])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[Sadrzi] CHECK CONSTRAINT [R_9]
GO
ALTER TABLE [dbo].[Transakcija]  WITH CHECK ADD  CONSTRAINT [R_30] FOREIGN KEY([IdPor])
REFERENCES [dbo].[Porudzbina] ([IdPor])
ON UPDATE CASCADE
GO
ALTER TABLE [dbo].[Transakcija] CHECK CONSTRAINT [R_30]
GO
ALTER TABLE [dbo].[Transakcija]  WITH CHECK ADD  CONSTRAINT [R_33] FOREIGN KEY([IdPro])
REFERENCES [dbo].[Prodavnica] ([IdPro])
GO
ALTER TABLE [dbo].[Transakcija] CHECK CONSTRAINT [R_33]
GO
ALTER TABLE [dbo].[Transakcija]  WITH CHECK ADD  CONSTRAINT [R_34] FOREIGN KEY([IdKup])
REFERENCES [dbo].[Kupac] ([IdKup])
GO
ALTER TABLE [dbo].[Transakcija] CHECK CONSTRAINT [R_34]
GO
/****** Object:  StoredProcedure [dbo].[SP_CENA_BEZ_POPUSTA]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[SP_CENA_BEZ_POPUSTA]
	@IdPor int, @ukupnaCena decimal(10, 3) output
AS
BEGIN


select @ukupnaCena = 0

-- pretpostavka da se popust ne menja dok artikli putuju
declare @sadrziKursor cursor;
declare @idArt int;
declare @kolicina int;

set @sadrziKursor = cursor for
select IdArt, kolicina from Sadrzi where IdPor = @IdPor
open @sadrziKursor
fetch next from @sadrziKursor into @idArt, @kolicina
while @@FETCH_STATUS = 0
begin
	declare @cenaArt decimal(10,3)
	select @cenaArt = Cena from Artikal where IdArt = @idArt

	declare @popust int
	select @popust = p.Popust from Artikal a join Oglasava o on a.IdArt = o.IdArt join Prodavnica p on o.IdPro = p.IdPro
	where a.IdArt = @idArt

	select @ukupnaCena = @ukupnaCena + @cenaArt * @kolicina * (1 - @popust / 100)

	fetch next from @sadrziKursor into @idArt, @kolicina
end


close @sadrziKursor
deallocate @sadrziKursor

END
GO
/****** Object:  StoredProcedure [dbo].[SP_FINAL_PRICE]    Script Date: 20/06/2023 00:55:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[SP_FINAL_PRICE]
	@IdPor int, @trenutniDatum datetime, @finalnaCena decimal(10, 3) output
AS
BEGIN

declare @ukupnaCena decimal(10, 3)
declare @status varchar(100)
declare @vremeSlanja datetime
declare @idKup int

select @ukupnaCena = 0

select @status = p.Stanje, @vremeSlanja = p.VremeSlanja, @idKup = p.IdKup
from Porudzbina p
where p.IdPor = @IdPor

-- pretpostavka da se popust ne menja dok artikli putuju
declare @sadrziKursor cursor;
declare @idArt int;
declare @kolicina int;

set @sadrziKursor = cursor for
select IdArt, Kolicina from Sadrzi where IdPor = @IdPor

open @sadrziKursor

fetch next from @sadrziKursor into @idArt, @kolicina
while @@FETCH_STATUS = 0
begin
	declare @cenaArt decimal(10,3)
	select @cenaArt = Cena from Artikal where IdArt = @idArt

	declare @popust int
	select @popust = p.Popust from Artikal a join Oglasava o on a.IdArt = o.IdArt join Prodavnica p on o.IdPro = p.IdPro
	where a.IdArt = @idArt

	select @ukupnaCena = @ukupnaCena + @cenaArt * @kolicina * (1 - 1.0 * @popust / 100)

	fetch next from @sadrziKursor into @idArt, @kolicina
end

declare @vreme datetime

if (@vremeSlanja is not null)
	select @vreme = @vremeSlanja
else 
	select @vreme = @trenutniDatum

declare @potroseno decimal(10, 3)
select @potroseno = sum(t.Novac)
from Transakcija t 
where t.IdKup = @idKup and DATEDIFF(day, t.Datum, @vreme) <= 30

if (@potroseno > 10000)
	select @finalnaCena = @ukupnaCena * 0.98
else 
	select @finalnaCena = @ukupnaCena

close @sadrziKursor
deallocate @sadrziKursor

END
GO

/****** Object:  Trigger [dbo].[TR_TRANSFER_MONEY_TO_SHOPS]    Script Date: 20/06/2023 01:02:22 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE TRIGGER [dbo].[TR_TRANSFER_MONEY_TO_SHOPS]
   ON  [dbo].[Porudzbina]
   AFTER UPDATE
AS 
BEGIN

declare @cursorInserted Cursor
declare @IdPor int
declare @Stanje varchar(100)
declare @VremePrijema datetime
declare @VremeSlanja datetime
declare @IdKup int

set @cursorInserted = cursor for
select IdPor, Stanje, VremePrijema, VremeSlanja, IdKup from inserted
open @cursorInserted
fetch next from @cursorInserted into @IdPor, @Stanje, @VremePrijema, @VremeSlanja, @IdKup
while @@FETCH_STATUS = 0
begin
	if (@Stanje <> 'arrived')
	begin
		fetch next from @cursorInserted into @IdPor, @Stanje, @VremePrijema, @VremeSlanja, @IdKup
		continue
	end
	
	declare @cena decimal(10, 3)
	declare @IdPro int
	declare @cursorTransakcije cursor
	declare @ukupnaCena decimal(10, 3)

	select @ukupnaCena = 0

	set @cursorTransakcije = cursor for
	select pro.IdPro, sum (a.Cena * s.Kolicina * (1 - 1.0 * pro.Popust / 100))
	from Porudzbina p join Sadrzi s on s.IdPor = p.IdPor join Artikal a on s.IdArt = a.IdArt
		join Oglasava o on o.IdArt = a.IdArt join Prodavnica pro on pro.IdPro = o.IdPro
	where p.IdPor = @IdPor
	group by pro.IdPro
	open @cursorTransakcije
	fetch next from @cursorTransakcije into @IdPro, @cena
	while @@FETCH_STATUS = 0
	begin
		insert into Transakcija (Novac, Datum, IdPor, IdPro, IdKup) 
									values (@cena * 1.0 * 95 / 100, @VremePrijema, @IdPor, @IdPro, null)
		select @ukupnaCena = @ukupnaCena + @cena
		fetch next from @cursorTransakcije into @IdPro, @cena
	end	
	
	close @cursorTransakcije
	deallocate @cursorTransakcije
	
	declare @potroseno decimal(10, 3)
	select @potroseno = sum(t.Novac)
	from Transakcija t 
	where t.IdKup = @idKup and DATEDIFF(day, t.Datum, @VremeSlanja) <= 30

	declare @profit decimal(10, 3)
	insert into Sistem (Profit) select 0 where not exists (select * from Sistem)
	if (@potroseno > 10000)
		select @profit = @ukupnaCena * 1.0 * 3 / 100
	else 
		select @profit = @ukupnaCena * 1.0 * 5 / 100

	update Sistem set Profit = Profit + @profit

	fetch next from @cursorInserted into @IdPor, @Stanje, @VremePrijema, @VremeSlanja, @IdKup
end

close @cursorInserted
deallocate @cursorInserted

END
GO

USE [master]
GO
ALTER DATABASE [prodavnice] SET  READ_WRITE 
GO
