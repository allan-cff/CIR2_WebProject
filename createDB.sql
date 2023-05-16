------------------------------------------------------------
--        Script Postgre 
------------------------------------------------------------


------------------------------------------------------------
-- DROP EXISTING TABLES
------------------------------------------------------------
DROP TABLE IF EXISTS public.user CASCADE;
DROP TABLE IF EXISTS public.teacher CASCADE;
DROP TABLE IF EXISTS public.cycle CASCADE;
DROP TABLE IF EXISTS public.campus CASCADE;
DROP TABLE IF EXISTS public.class CASCADE;
DROP TABLE IF EXISTS public.student CASCADE;
DROP TABLE IF EXISTS public.admin CASCADE;
DROP TABLE IF EXISTS public.semester CASCADE;
DROP TABLE IF EXISTS public.appreciation CASCADE;
DROP TABLE IF EXISTS public.matter CASCADE;
DROP TABLE IF EXISTS public.lesson CASCADE;
DROP TABLE IF EXISTS public.evaluation CASCADE;
DROP TABLE IF EXISTS public.grade CASCADE;


------------------------------------------------------------
-- Table: cycle
------------------------------------------------------------
CREATE TABLE public.cycle(
	cycle_ID   INT GENERATED ALWAYS AS IDENTITY ,
	cycle      VARCHAR (20) NOT NULL  ,
	CONSTRAINT cycle_PK PRIMARY KEY (cycle_id),
	CONSTRAINT cycle_AK UNIQUE (cycle)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: campus
------------------------------------------------------------
CREATE TABLE public.campus(
	campus_ID     INT GENERATED ALWAYS AS IDENTITY ,
	campus_name   VARCHAR (20) NOT NULL ,
	latitude      FLOAT8 ,
	longitude     FLOAT8 ,
	CONSTRAINT campus_PK PRIMARY KEY (campus_id),
	CONSTRAINT campus_name_AK UNIQUE (campus_name),
	CONSTRAINT campus_location_AK UNIQUE (latitude, longitude)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: class
------------------------------------------------------------
CREATE TABLE public.class(
	class_ID     INT GENERATED ALWAYS AS IDENTITY ,
	class_name   VARCHAR (7) NOT NULL ,
	first_year        SMALLINT  NOT NULL ,
	graduation_year   SMALLINT  NOT NULL ,
	campus_id    INT  NOT NULL ,
	cycle_id     INT  NOT NULL  ,
	CONSTRAINT class_PK PRIMARY KEY (class_ID),
	CONSTRAINT class_campus_FK FOREIGN KEY (campus_id) REFERENCES public.campus(campus_id),
	CONSTRAINT class_cycle_FK FOREIGN KEY (cycle_id) REFERENCES public.cycle(cycle_id),
	CONSTRAINT class_AK UNIQUE (class_name, first_year, graduation_year, campus_id, cycle_id)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: user
------------------------------------------------------------
CREATE TABLE public.user(
	mail         VARCHAR (50) NOT NULL ,
	name         VARCHAR (20) NOT NULL ,
	surname      VARCHAR (20) NOT NULL ,
	password     VARCHAR (75) NOT NULL ,
	last_login   DATE ,
	phone        VARCHAR (10)  NOT NULL  ,
	CONSTRAINT user_PK PRIMARY KEY (mail),
	CONSTRAINT user_AK UNIQUE (phone)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: teacher
------------------------------------------------------------
CREATE TABLE public.teacher(
	mail         VARCHAR (50) NOT NULL,
	CONSTRAINT teacher_PK PRIMARY KEY (mail),
	CONSTRAINT teacher_user_FK FOREIGN KEY (mail) REFERENCES public.user(mail) ON DELETE CASCADE
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: student
------------------------------------------------------------
CREATE TABLE public.student(
	mail         VARCHAR (50) NOT NULL ,
	student_ID   INT  NOT NULL ,
	class_ID     INT  NOT NULL  ,
	CONSTRAINT student_PK PRIMARY KEY (mail),
	CONSTRAINT student_AK UNIQUE (student_ID),
	CONSTRAINT student_user_FK FOREIGN KEY (mail) REFERENCES public.user(mail) ON DELETE CASCADE,
	CONSTRAINT student_class0_FK FOREIGN KEY (class_ID) REFERENCES public.class(class_ID) ON DELETE CASCADE
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: admin
------------------------------------------------------------
CREATE TABLE public.admin(
	mail       VARCHAR (50) NOT NULL  ,
	CONSTRAINT admin_PK PRIMARY KEY (mail),
	CONSTRAINT admin_user_FK FOREIGN KEY (mail) REFERENCES public.user(mail) ON DELETE CASCADE
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: semester
------------------------------------------------------------
CREATE TABLE public.semester(
	semester_ID   INT GENERATED ALWAYS AS IDENTITY ,
	date_begin    DATE  NOT NULL ,
	date_end      DATE  NOT NULL  ,
	semester_name   VARCHAR (30) NOT NULL  ,
	CONSTRAINT semester_PK PRIMARY KEY (semester_ID),
	CONSTRAINT semester_AK UNIQUE (date_begin, date_end)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: appreciation
------------------------------------------------------------
CREATE TABLE public.appreciation(
	appreciation_ID   INT GENERATED ALWAYS AS IDENTITY ,
	appraisal         VARCHAR (200) NOT NULL ,
	semester_ID       INT  NOT NULL ,
	mail              VARCHAR (50) NOT NULL  ,
	CONSTRAINT appreciation_PK PRIMARY KEY (appreciation_ID),
	CONSTRAINT appreciation_semester_FK FOREIGN KEY (semester_ID) REFERENCES public.semester(semester_ID) ON DELETE CASCADE,
	CONSTRAINT appreciation_student0_FK FOREIGN KEY (mail) REFERENCES public.student(mail) ON DELETE CASCADE,
	CONSTRAINT appreciation_AK UNIQUE (mail, semester_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: matter
------------------------------------------------------------
CREATE TABLE public.matter(
	matter_ID   INT GENERATED ALWAYS AS IDENTITY ,
	subject     VARCHAR (25) NOT NULL  ,
	CONSTRAINT matter_PK PRIMARY KEY (matter_ID),
	CONSTRAINT matter_AK UNIQUE (subject)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: lesson
------------------------------------------------------------
CREATE TABLE public.lesson(
	lesson_ID     INT GENERATED ALWAYS AS IDENTITY ,
	class_ID      INT  NOT NULL ,
	teacher    VARCHAR (50) NOT NULL ,
	semester_ID   INT  NOT NULL  ,
	matter_ID     INT  NOT NULL  ,
	CONSTRAINT lesson_PK PRIMARY KEY (lesson_ID),
	CONSTRAINT lesson_class_FK FOREIGN KEY (class_ID) REFERENCES public.class(class_ID) ON DELETE CASCADE,
	CONSTRAINT lesson_teacher0_FK FOREIGN KEY (teacher) REFERENCES public.teacher(mail) ON DELETE CASCADE,
	CONSTRAINT lesson_semester1_FK FOREIGN KEY (semester_ID) REFERENCES public.semester(semester_ID) ON DELETE CASCADE,
	CONSTRAINT lesson_matter2_FK FOREIGN KEY (matter_ID) REFERENCES public.matter(matter_ID) ON DELETE CASCADE,
	CONSTRAINT lesson_AK UNIQUE (class_ID, matter_ID, semester_ID, teacher)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: evaluation
------------------------------------------------------------
CREATE TABLE public.evaluation(
	eval_ID     	INT GENERATED ALWAYS AS IDENTITY ,
	coeff       	FLOAT  NOT NULL ,
	begin_datetime  TIMESTAMP WITH TIME ZONE NOT NULL ,
	end_datetime    TIMESTAMP WITH TIME ZONE NOT NULL ,
	description     VARCHAR (100)  ,
	lesson_ID   	INT  NOT NULL  ,
	CONSTRAINT evaluation_PK PRIMARY KEY (eval_ID),
	CONSTRAINT evaluation_lesson_FK FOREIGN KEY (lesson_ID) REFERENCES public.lesson(lesson_ID) ON DELETE CASCADE,
	CONSTRAINT evaluation_AK UNIQUE (lesson_ID, begin_datetime)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: grade
------------------------------------------------------------
CREATE TABLE public.grade(
	grade_ID     INT GENERATED ALWAYS AS IDENTITY ,
	grade        FLOAT  NOT NULL ,
	eval_ID      INT  NOT NULL ,
	mail       VARCHAR (50) NOT NULL  ,
	CONSTRAINT grade_PK PRIMARY KEY (grade_ID),
	CONSTRAINT grade_evaluation_FK FOREIGN KEY (eval_ID) REFERENCES public.evaluation(eval_ID) ON DELETE CASCADE,
	CONSTRAINT grade_student0_FK FOREIGN KEY (mail) REFERENCES public.student(mail) ON DELETE CASCADE,
	CONSTRAINT grade_AK UNIQUE (eval_ID, mail)
)WITHOUT OIDS;


------------------------------------------------------------
-- INSERT TEST DATA
------------------------------------------------------------
INSERT INTO public.cycle(cycle) VALUES('CIR');
INSERT INTO public.cycle(cycle) VALUES('EST');
INSERT INTO public.cycle(cycle) VALUES('BIOST');
INSERT INTO public.cycle(cycle) VALUES('CENT');
INSERT INTO public.cycle(cycle) VALUES('MECA');
INSERT INTO public.cycle(cycle) VALUES('CSI');

INSERT INTO public.campus(campus_name, latitude, longitude) VALUES('Nantes', -1.507627, 47.27487);
INSERT INTO public.campus(campus_name, latitude, longitude) VALUES('Rennes', -1.6926634, 48.127754);
INSERT INTO public.campus(campus_name, latitude, longitude) VALUES('Caen', -0.3562123, 49.189164);
INSERT INTO public.campus(campus_name, latitude, longitude) VALUES('Brest', -4.4981289, 48.4070589);

INSERT INTO public.matter(subject) VALUES('Algorithmique - C++');
INSERT INTO public.matter(subject) VALUES('FHS');
INSERT INTO public.matter(subject) VALUES('Mathématiques');
INSERT INTO public.matter(subject) VALUES('Anglais');
INSERT INTO public.matter(subject) VALUES('Algorithmique - Python');
INSERT INTO public.matter(subject) VALUES('Serveurs Web');
INSERT INTO public.matter(subject) VALUES('Communication Web');
INSERT INTO public.matter(subject) VALUES('Web Backend');

INSERT INTO public.semester(date_begin, date_end, semester_name) VALUES('2022-09-01', '2023-02-15', 'S1 2022/2023');
INSERT INTO public.semester(date_begin, date_end, semester_name) VALUES('2023-02-16', '2023-06-15', 'S2 2022/2023');
INSERT INTO public.semester(date_begin, date_end, semester_name) VALUES('2023-09-03', '2024-02-11', 'S1 2023/2024');
INSERT INTO public.semester(date_begin, date_end, semester_name) VALUES('2024-02-12', '2024-06-17', 'S2 2023/2024');

INSERT INTO public.class(class_name, first_year, graduation_year, campus_id, cycle_id) VALUES('Promo 5', 2021, 2026, (SELECT campus_id FROM public.campus WHERE campus_name = 'Nantes'), (SELECT cycle_id FROM public.cycle WHERE cycle = 'CIR'));
INSERT INTO public.class(class_name, first_year, graduation_year, campus_id, cycle_id) VALUES('Promo 6', 2022, 2027, (SELECT campus_id FROM public.campus WHERE campus_name = 'Nantes'), (SELECT cycle_id FROM public.cycle WHERE cycle = 'CIR'));
INSERT INTO public.class(class_name, first_year, graduation_year, campus_id, cycle_id) VALUES('Promo 5', 2021, 2026, (SELECT campus_id FROM public.campus WHERE campus_name = 'Nantes'), (SELECT cycle_id FROM public.cycle WHERE cycle = 'CSI'));
INSERT INTO public.class(class_name, first_year, graduation_year, campus_id, cycle_id) VALUES('Promo 5', 2021, 2026, (SELECT campus_id FROM public.campus WHERE campus_name = 'Nantes'), (SELECT cycle_id FROM public.cycle WHERE cycle = 'EST'));


INSERT INTO public.user VALUES('lara.clette@isen.fr', 'Clette', 'Lara', '$2y$10$7.gHsr3BZbDn3xnvU1yqrON49S6GdgO.3RoH0Tr6jz3QD8qzk4jDK', NULL, '0612345678');
INSERT INTO public.student(mail, student_ID, class_ID) VALUES('lara.clette@isen.fr', 70012, 1);
INSERT INTO public.user VALUES('jacques.ouzi@isen.fr', 'Ouzi', 'Jacques', '$2y$10$7.gHsr3BZbDn3xnvU1yqrON49S6GdgO.3RoH0Tr6jz3QD8qzk4jDK', NULL, '0612345679');
INSERT INTO public.student(mail, student_ID, class_ID) VALUES('jacques.ouzi@isen.fr', 70013, 1);
INSERT INTO public.user VALUES('bernard.tichaud@isen.fr', 'Tichaud', 'Bernard', '$2y$10$7.gHsr3BZbDn3xnvU1yqrON49S6GdgO.3RoH0Tr6jz3QD8qzk4jDK', NULL, '0612345681');
INSERT INTO public.student(mail, student_ID, class_ID) VALUES('bernard.tichaud@isen.fr', 70014, 1);
INSERT INTO public.user VALUES('alain.terieur@isen.fr', 'Terieur', 'Alain', '$2y$10$7.gHsr3BZbDn3xnvU1yqrON49S6GdgO.3RoH0Tr6jz3QD8qzk4jDK', NULL, '0612345683');
INSERT INTO public.student(mail, student_ID, class_ID) VALUES('alain.terieur@isen.fr', 70016, 1);
INSERT INTO public.user VALUES('abel.auboisdormant@isen.fr', 'Auboisdormant', 'Abel', '$2y$10$7.gHsr3BZbDn3xnvU1yqrON49S6GdgO.3RoH0Tr6jz3QD8qzk4jDK', NULL, '0612345684');
INSERT INTO public.student(mail, student_ID, class_ID) VALUES('abel.auboisdormant@isen.fr', 70017, 1);

INSERT INTO public.user VALUES('ayoub.karine@isen.fr', 'Dubois', 'Maurice', '$2y$10$7.gHsr3BZbDn3xnvU1yqrON49S6GdgO.3RoH0Tr6jz3QD8qzk4jDK', NULL, '0612345685');
INSERT INTO public.teacher VALUES('ayoub.karine@isen.fr');
INSERT INTO public.user VALUES('abdelhaq.abdelqari@isen.fr', 'Dubois', 'Maurice', '$2y$10$7.gHsr3BZbDn3xnvU1yqrON49S6GdgO.3RoH0Tr6jz3QD8qzk4jDK', NULL, '0635345685');
INSERT INTO public.teacher VALUES('abdelhaq.abdelqari@isen.fr');
INSERT INTO public.user VALUES('leandro.montero@isen.fr', 'Dubois', 'Maurice', '$2y$10$7.gHsr3BZbDn3xnvU1yqrON49S6GdgO.3RoH0Tr6jz3QD8qzk4jDK', NULL, '0619945685');
INSERT INTO public.teacher VALUES('leandro.montero@isen.fr');
INSERT INTO public.user VALUES('mateo.sorin@isen.fr', 'Dubois', 'Maurice', '$2y$10$7.gHsr3BZbDn3xnvU1yqrON49S6GdgO.3RoH0Tr6jz3QD8qzk4jDK', NULL, '0612349185');
INSERT INTO public.teacher VALUES('mateo.sorin@isen.fr');

INSERT INTO public.lesson(class_id, teacher, semester_id, matter_id) VALUES(1, 'ayoub.karine@isen.fr', 1, (SELECT matter_id FROM public.matter WHERE subject = 'Web Backend'));
INSERT INTO public.lesson(class_id, teacher, semester_id, matter_id) VALUES(1, 'ayoub.karine@isen.fr', 1, (SELECT matter_id FROM public.matter WHERE subject = 'Communication Web'));
INSERT INTO public.lesson(class_id, teacher, semester_id, matter_id) VALUES(1, 'mateo.sorin@isen.fr', 1, (SELECT matter_id FROM public.matter WHERE subject = 'FHS'));
INSERT INTO public.lesson(class_id, teacher, semester_id, matter_id) VALUES(1, 'abdelhaq.abdelqari@isen.fr', 1, (SELECT matter_id FROM public.matter WHERE subject = 'Mathématiques'));

INSERT INTO public.evaluation(coeff, begin_datetime, end_datetime, description, lesson_id) VALUES (1 , '2022-12-16 08:00:00', '2022-12-16 09:30:00', 'DS1', 1);
INSERT INTO public.evaluation(coeff, begin_datetime, end_datetime, description, lesson_id) VALUES (1 , '2023-01-15 08:00:00', '2023-01-15 09:30:00', 'DS1', 2);
INSERT INTO public.evaluation(coeff, begin_datetime, end_datetime, description, lesson_id) VALUES (2 , '2023-02-08 08:00:00', '2023-02-08 09:30:00', 'DS2', 1);

INSERT INTO public.grade(eval_id, grade, mail) VALUES (1, 18, 'lara.clette@isen.fr');
INSERT INTO public.grade(eval_id, grade, mail) VALUES (1, 12.5, 'jacques.ouzi@isen.fr');
INSERT INTO public.grade(eval_id, grade, mail) VALUES (1, 13.75, 'bernard.tichaud@isen.fr');
INSERT INTO public.grade(eval_id, grade, mail) VALUES (1, 9, 'alain.terieur@isen.fr');
INSERT INTO public.grade(eval_id, grade, mail) VALUES (1, 7, 'abel.auboisdormant@isen.fr');

INSERT INTO public.grade(eval_id, grade, mail) VALUES (2, 11, 'lara.clette@isen.fr');
INSERT INTO public.grade(eval_id, grade, mail) VALUES (2, 14, 'jacques.ouzi@isen.fr');
INSERT INTO public.grade(eval_id, grade, mail) VALUES (2, 5, 'bernard.tichaud@isen.fr');
INSERT INTO public.grade(eval_id, grade, mail) VALUES (2, 8.5, 'alain.terieur@isen.fr');
INSERT INTO public.grade(eval_id, grade, mail) VALUES (2, 10, 'abel.auboisdormant@isen.fr');

INSERT INTO public.grade(eval_id, grade, mail) VALUES (3, 14, 'lara.clette@isen.fr');
INSERT INTO public.grade(eval_id, grade, mail) VALUES (3, 17, 'jacques.ouzi@isen.fr');
INSERT INTO public.grade(eval_id, grade, mail) VALUES (3, 15, 'bernard.tichaud@isen.fr');

INSERT INTO public.appreciation(appraisal, semester_id, mail) VALUES('Très bon semestre', 1, 'lara.clette@isen.fr');
INSERT INTO public.appreciation(appraisal, semester_id, mail) VALUES('Très bon semestre', 1, 'jacques.ouzi@isen.fr');
INSERT INTO public.appreciation(appraisal, semester_id, mail) VALUES('Manque de travail', 1, 'alain.terieur@isen.fr');

INSERT INTO public.user VALUES('allan@isen.fr', 'Cueff', 'Allan', '$2y$10$7.gHsr3BZbDn3xnvU1yqrON49S6GdgO.3RoH0Tr6jz3QD8qzk4jDK', NULL, '0616155975');
INSERT INTO public.admin VALUES('allan@isen.fr');