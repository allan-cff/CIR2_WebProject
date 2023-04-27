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
DROP TABLE IF EXISTS public.lesson CASCADE;
DROP TABLE IF EXISTS public.evaluation CASCADE;
DROP TABLE IF EXISTS public.grade CASCADE;


------------------------------------------------------------
-- Table: cycle
------------------------------------------------------------
CREATE TABLE public.cycle(
	cycle_id   INT GENERATED ALWAYS AS IDENTITY ,
	cycle      VARCHAR (20) NOT NULL  ,
	CONSTRAINT cycle_PK PRIMARY KEY (cycle_id),
	CONSTRAINT cycle_AK UNIQUE (cycle)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: campus
------------------------------------------------------------
CREATE TABLE public.campus(
	campus_id     INT GENERATED ALWAYS AS IDENTITY ,
	campus_name   VARCHAR (20) NOT NULL ,
	latitude      FLOAT8  NOT NULL ,
	longitude     FLOAT8  NOT NULL  ,
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
	study_year         INT2  NOT NULL ,
	campus_id    INT  NOT NULL ,
	cycle_id     INT  NOT NULL  ,
	CONSTRAINT class_PK PRIMARY KEY (class_ID),
	CONSTRAINT class_campus_FK FOREIGN KEY (campus_id) REFERENCES public.campus(campus_id),
	CONSTRAINT class_cycle_FK FOREIGN KEY (cycle_id) REFERENCES public.cycle(cycle_id),
	CONSTRAINT class_AK UNIQUE (class_name, study_year, campus_id, cycle_id)
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
	student_ID   INT GENERATED ALWAYS AS IDENTITY ,
	mail         VARCHAR (50) NOT NULL ,
	class_ID     INT  NOT NULL  ,
	CONSTRAINT student_PK PRIMARY KEY (student_ID),
	CONSTRAINT student_AK UNIQUE (mail),
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
	student_ID        INT  NOT NULL  ,
	CONSTRAINT appreciation_PK PRIMARY KEY (appreciation_ID),
	CONSTRAINT appreciation_semester_FK FOREIGN KEY (semester_ID) REFERENCES public.semester(semester_ID) ON DELETE CASCADE,
	CONSTRAINT appreciation_student0_FK FOREIGN KEY (student_ID) REFERENCES public.student(student_ID) ON DELETE CASCADE
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: lesson
------------------------------------------------------------
CREATE TABLE public.lesson(
	lesson_ID     INT GENERATED ALWAYS AS IDENTITY ,
	subject       VARCHAR (20) NOT NULL ,
	class_ID      INT  NOT NULL ,
	teacher    VARCHAR (50) NOT NULL ,
	semester_ID   INT  NOT NULL  ,
	CONSTRAINT lesson_PK PRIMARY KEY (lesson_ID),
	CONSTRAINT lesson_class_FK FOREIGN KEY (class_ID) REFERENCES public.class(class_ID) ON DELETE CASCADE,
	CONSTRAINT lesson_teacher0_FK FOREIGN KEY (teacher) REFERENCES public.teacher(mail) ON DELETE CASCADE,
	CONSTRAINT lesson_semester1_FK FOREIGN KEY (semester_ID) REFERENCES public.semester(semester_ID) ON DELETE CASCADE
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: evaluation
------------------------------------------------------------
CREATE TABLE public.evaluation(
	eval_ID     INT GENERATED ALWAYS AS IDENTITY ,
	coeff       FLOAT  NOT NULL ,
	lesson_ID   INT  NOT NULL  ,
	CONSTRAINT evaluation_PK PRIMARY KEY (eval_ID),
	CONSTRAINT evaluation_lesson_FK FOREIGN KEY (lesson_ID) REFERENCES public.lesson(lesson_ID) ON DELETE CASCADE
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: grade
------------------------------------------------------------
CREATE TABLE public.grade(
	grade_ID     INT GENERATED ALWAYS AS IDENTITY ,
	grade        FLOAT  NOT NULL ,
	eval_ID      INT  NOT NULL ,
	student_ID   INT  NOT NULL  ,
	CONSTRAINT grade_PK PRIMARY KEY (grade_ID),
	CONSTRAINT grade_evaluation_FK FOREIGN KEY (eval_ID) REFERENCES public.evaluation(eval_ID) ON DELETE CASCADE,
	CONSTRAINT grade_student0_FK FOREIGN KEY (student_ID) REFERENCES public.student(student_ID) ON DELETE CASCADE
)WITHOUT OIDS;


------------------------------------------------------------
-- INSERT TEST DATA
------------------------------------------------------------
INSERT INTO public.cycle(cycle) VALUES('CIR');
INSERT INTO public.campus(campus_name, latitude, longitude) VALUES('Nantes', 1, 1);
INSERT INTO public.class(class_name, study_year, campus_id, cycle_id) VALUES('CIR2', 2, (SELECT campus_id FROM public.campus WHERE campus_name = 'Nantes'), (SELECT cycle_id FROM public.cycle WHERE cycle = 'CIR'));
INSERT INTO public.user VALUES('lara.clette@messagerie.fr', 'Clette', 'Lara', 'test', NULL, '0612345678');
INSERT INTO public.student(mail, class_ID) VALUES('lara.clette@messagerie.fr', (SELECT class_id FROM public.class WHERE class_name = 'CIR2'));
INSERT INTO public.user VALUES('jacques.ouzi@messagerie.fr', 'Ouzi', 'Jacques', 'test', NULL, '0612345679');
INSERT INTO public.student(mail, class_ID) VALUES('jacques.ouzi@messagerie.fr', (SELECT class_id FROM public.class WHERE class_name = 'CIR2'));
INSERT INTO public.user VALUES('line.stah@messagerie.fr', 'Stah', 'Line', 'test', NULL, '0612345680');
INSERT INTO public.student(mail, class_ID) VALUES('line.stah@messagerie.fr', (SELECT class_id FROM public.class WHERE class_name = 'CIR2'));
INSERT INTO public.user VALUES('bernard.tichaud@messagerie.fr', 'Tichaud', 'Bernard', 'test', NULL, '0612345681');
INSERT INTO public.student(mail, class_ID) VALUES('bernard.tichaud@messagerie.fr', (SELECT class_id FROM public.class WHERE class_name = 'CIR2'));
INSERT INTO public.user VALUES('annalise.durine@messagerie.fr', 'Durine', 'Anna-Lise', 'test', NULL, '0612345682');
INSERT INTO public.student(mail, class_ID) VALUES('annalise.durine@messagerie.fr', (SELECT class_id FROM public.class WHERE class_name = 'CIR2'));
INSERT INTO public.user VALUES('alain.terieur@messagerie.fr', 'Terieur', 'Alain', 'test', NULL, '0612345683');
INSERT INTO public.student(mail, class_ID) VALUES('alain.terieur@messagerie.fr', (SELECT class_id FROM public.class WHERE class_name = 'CIR2'));
INSERT INTO public.user VALUES('abel.auboisdormant@messagerie.fr', 'Auboisdormant', 'Abel', 'test', NULL, '0612345684');
INSERT INTO public.student(mail, class_ID) VALUES('abel.auboisdormant@messagerie.fr', (SELECT class_id FROM public.class WHERE class_name = 'CIR2'));
INSERT INTO public.user VALUES('maurice.dubois@messagerie.fr', 'Dubois', 'Maurice', 'test', NULL, '0612345685');
INSERT INTO public.teacher VALUES('maurice.dubois@messagerie.fr');
INSERT INTO public.semester(date_begin, date_end) VALUES('2023-09-01', '2024-02-15');
INSERT INTO public.lesson(subject, class_id, teacher, semester_id) VALUES('Algorithmique - C++', (SELECT class_id FROM public.class WHERE class_name = 'CIR2'), 'maurice.dubois@messagerie.fr', (SELECT semester_id FROM public.semester WHERE date_begin = '2023-09-01 8:00:00.000'));
INSERT INTO public.user VALUES('allan@isen.fr', 'Cueff', 'Allan', 'test', NULL, '0616155975');
INSERT INTO public.admin VALUES('allan@isen.fr');