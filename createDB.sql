------------------------------------------------------------
--        Script Postgre 
------------------------------------------------------------


------------------------------------------------------------
-- DROP EXISTING TABLES
------------------------------------------------------------
DROP TABLE IF EXISTS public.user CASCADE;
DROP TABLE IF EXISTS public.teacher CASCADE;
DROP TABLE IF EXISTS public.class CASCADE;
DROP TABLE IF EXISTS public.student CASCADE;
DROP TABLE IF EXISTS public.admin CASCADE;
DROP TABLE IF EXISTS public.semester CASCADE;
DROP TABLE IF EXISTS public.appreciation CASCADE;
DROP TABLE IF EXISTS public.lesson CASCADE;
DROP TABLE IF EXISTS public.evaluation CASCADE;
DROP TABLE IF EXISTS public.grade CASCADE;



------------------------------------------------------------
-- Table: user
------------------------------------------------------------
CREATE TABLE public.user(
	mail         VARCHAR (50) NOT NULL ,
	name         VARCHAR (20) NOT NULL ,
	surname      VARCHAR (20) NOT NULL ,
	password     VARCHAR (75) NOT NULL ,
	last_login   DATE  NOT NULL ,
	phone        VARCHAR (10)  NOT NULL  ,
	CONSTRAINT user_PK PRIMARY KEY (mail) ,
	CONSTRAINT user_AK UNIQUE (phone)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: teacher
------------------------------------------------------------
CREATE TABLE public.teacher(
	teacher_ID   int GENERATED ALWAYS AS IDENTITY ,
	mail         VARCHAR (50) NOT NULL  ,
	CONSTRAINT teacher_PK PRIMARY KEY (teacher_ID)

	,CONSTRAINT teacher_user_FK FOREIGN KEY (mail) REFERENCES public.user(mail)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: class
------------------------------------------------------------
CREATE TABLE public.class(
	class_ID   int GENERATED ALWAYS AS IDENTITY ,
	cycle      VARCHAR (7) NOT NULL  ,
	CONSTRAINT class_PK PRIMARY KEY (class_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: student
------------------------------------------------------------
CREATE TABLE public.student(
	student_ID   int GENERATED ALWAYS AS IDENTITY ,
	mail         VARCHAR (50) NOT NULL ,
	class_ID     INT  NOT NULL  ,
	CONSTRAINT student_PK PRIMARY KEY (student_ID)

	,CONSTRAINT student_user_FK FOREIGN KEY (mail) REFERENCES public.user(mail)
	,CONSTRAINT student_class0_FK FOREIGN KEY (class_ID) REFERENCES public.class(class_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: admin
------------------------------------------------------------
CREATE TABLE public.admin(
	admin_ID   int GENERATED ALWAYS AS IDENTITY ,
	mail       VARCHAR (50) NOT NULL  ,
	CONSTRAINT admin_PK PRIMARY KEY (admin_ID)

	,CONSTRAINT admin_user_FK FOREIGN KEY (mail) REFERENCES public.user(mail)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: semester
------------------------------------------------------------
CREATE TABLE public.semester(
	semester_ID   int GENERATED ALWAYS AS IDENTITY ,
	date_begin    DATE  NOT NULL ,
	date_end      DATE  NOT NULL  ,
	CONSTRAINT semester_PK PRIMARY KEY (semester_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: appreciation
------------------------------------------------------------
CREATE TABLE public.appreciation(
	appreciation_ID   int GENERATED ALWAYS AS IDENTITY ,
	appraisal         VARCHAR (200) NOT NULL ,
	semester_ID       INT  NOT NULL ,
	student_ID        INT  NOT NULL  ,
	CONSTRAINT appreciation_PK PRIMARY KEY (appreciation_ID)

	,CONSTRAINT appreciation_semester_FK FOREIGN KEY (semester_ID) REFERENCES public.semester(semester_ID)
	,CONSTRAINT appreciation_student0_FK FOREIGN KEY (student_ID) REFERENCES public.student(student_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: lesson
------------------------------------------------------------
CREATE TABLE public.lesson(
	lesson_ID     int GENERATED ALWAYS AS IDENTITY ,
	subject       VARCHAR (20) NOT NULL ,
	class_ID      INT  NOT NULL ,
	teacher_ID    INT  NOT NULL ,
	semester_ID   INT  NOT NULL  ,
	CONSTRAINT lesson_PK PRIMARY KEY (lesson_ID)

	,CONSTRAINT lesson_class_FK FOREIGN KEY (class_ID) REFERENCES public.class(class_ID)
	,CONSTRAINT lesson_teacher0_FK FOREIGN KEY (teacher_ID) REFERENCES public.teacher(teacher_ID)
	,CONSTRAINT lesson_semester1_FK FOREIGN KEY (semester_ID) REFERENCES public.semester(semester_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: evaluation
------------------------------------------------------------
CREATE TABLE public.evaluation(
	eval_ID     int GENERATED ALWAYS AS IDENTITY ,
	coeff       FLOAT  NOT NULL ,
	lesson_ID   INT  NOT NULL  ,
	CONSTRAINT evaluation_PK PRIMARY KEY (eval_ID)

	,CONSTRAINT evaluation_lesson_FK FOREIGN KEY (lesson_ID) REFERENCES public.lesson(lesson_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: grade
------------------------------------------------------------
CREATE TABLE public.grade(
	grade_ID     int GENERATED ALWAYS AS IDENTITY ,
	grade        FLOAT  NOT NULL ,
	eval_ID      INT  NOT NULL ,
	student_ID   INT  NOT NULL  ,
	CONSTRAINT grade_PK PRIMARY KEY (grade_ID)

	,CONSTRAINT grade_evaluation_FK FOREIGN KEY (eval_ID) REFERENCES public.evaluation(eval_ID)
	,CONSTRAINT grade_student0_FK FOREIGN KEY (student_ID) REFERENCES public.student(student_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- INSERT TEST DATA
------------------------------------------------------------
INSERT INTO public.class VALUES(DEFAULT, 'CIR2');
INSERT INTO public.user VALUES('lara.clette@messagerie.fr', 'Clette', 'Lara', 'test', '11/04/2023 15:30:00.000', '0612345678');
INSERT INTO public.student(mail, class_ID) VALUES('lara.clette@messagerie.fr', (SELECT class_id FROM class WHERE cycle = 'CIR2'));
INSERT INTO public.user VALUES('jacques.ouzi@messagerie.fr', 'Ouzi', 'Jacques', 'test', '2023-11-04 15:30:00.000', '0612345679');
INSERT INTO public.student(mail, class_ID) VALUES('jacques.ouzi@messagerie.fr', (SELECT class_id FROM class WHERE cycle = 'CIR2'));
INSERT INTO public.user VALUES('line.stah@messagerie.fr', 'Stah', 'Line', 'test', '2023-11-04 15:30:00.000', '0612345680');
INSERT INTO public.student(mail, class_ID) VALUES('line.stah@messagerie.fr', (SELECT class_id FROM class WHERE cycle = 'CIR2'));
INSERT INTO public.user VALUES('bernard.tichaud@messagerie.fr', 'Tichaud', 'Bernard', 'test', '2023-11-04 15:30:00.000', '0612345681');
INSERT INTO public.student(mail, class_ID) VALUES('bernard.tichaud@messagerie.fr', (SELECT class_id FROM class WHERE cycle = 'CIR2'));
INSERT INTO public.user VALUES('annalise.durine@messagerie.fr', 'Durine', 'Anna-Lise', 'test', '2023-11-04 15:30:00.000', '0612345682');
INSERT INTO public.student(mail, class_ID) VALUES('annalise.durine@messagerie.fr', (SELECT class_id FROM class WHERE cycle = 'CIR2'));
INSERT INTO public.user VALUES('alain.terieur@messagerie.fr', 'Terieur', 'Alain', 'test', '2023-11-04 15:30:00.000', '0612345683');
INSERT INTO public.student(mail, class_ID) VALUES('alain.terieur@messagerie.fr', (SELECT class_id FROM class WHERE cycle = 'CIR2'));
INSERT INTO public.user VALUES('abel.auboisdormant@messagerie.fr', 'Auboisdormant', 'Abel', 'test', '2023-11-04 15:30:00.000', '0612345684');
INSERT INTO public.student(mail, class_ID) VALUES('abel.auboisdormant@messagerie.fr', (SELECT class_id FROM class WHERE cycle = 'CIR2'));
INSERT INTO public.user VALUES('maurice.dubois@messagerie.fr', 'Dubois', 'Maurice', 'test', '2023-11-04 15:30:00.000', '0612345685');
INSERT INTO public.teacher(mail) VALUES('maurice.dubois@messagerie.fr');
INSERT INTO public.semester(date_begin, date_end) VALUES('2023-09-01 8:00:00.000', '2024-02-15 8:00:00.000');
INSERT INTO public.lesson(subject, class_id, teacher_id, semester_id) VALUES('Algorithmique - C++', (SELECT class_id FROM class WHERE cycle = 'CIR2'), (SELECT teacher_id FROM teacher WHERE mail = 'maurice.dubois@messagerie.fr'), (SELECT semester_id FROM semester WHERE date_begin = '2023-09-01 8:00:00.000'));