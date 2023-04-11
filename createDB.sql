------------------------------------------------------------
--        Script Postgre 
------------------------------------------------------------



------------------------------------------------------------
-- Table: user
------------------------------------------------------------
CREATE TABLE public.user(
	mail         VARCHAR (50) NOT NULL ,
	name         VARCHAR (20) NOT NULL ,
	surname      VARCHAR (20) NOT NULL ,
	password     VARCHAR (75) NOT NULL ,
	last_login   DATE  NOT NULL ,
	phone        INT  NOT NULL  ,
	CONSTRAINT user_PK PRIMARY KEY (mail) ,
	CONSTRAINT user_AK UNIQUE (phone)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: teacher
------------------------------------------------------------
CREATE TABLE public.teacher(
	teacher_ID   SERIAL NOT NULL ,
	mail         VARCHAR (50) NOT NULL  ,
	CONSTRAINT teacher_PK PRIMARY KEY (teacher_ID)

	,CONSTRAINT teacher_user_FK FOREIGN KEY (mail) REFERENCES public.user(mail)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: class
------------------------------------------------------------
CREATE TABLE public.class(
	class_ID   SERIAL NOT NULL ,
	cycle      VARCHAR (7) NOT NULL  ,
	CONSTRAINT class_PK PRIMARY KEY (class_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: student
------------------------------------------------------------
CREATE TABLE public.student(
	student_ID   SERIAL NOT NULL ,
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
	admin_ID   SERIAL NOT NULL ,
	mail       VARCHAR (50) NOT NULL  ,
	CONSTRAINT admin_PK PRIMARY KEY (admin_ID)

	,CONSTRAINT admin_user_FK FOREIGN KEY (mail) REFERENCES public.user(mail)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: semester
------------------------------------------------------------
CREATE TABLE public.semester(
	semester_ID   SERIAL NOT NULL ,
	date_begin    DATE  NOT NULL ,
	date_end      DATE  NOT NULL  ,
	CONSTRAINT semester_PK PRIMARY KEY (semester_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: appreciation
------------------------------------------------------------
CREATE TABLE public.appreciation(
	appreciation_ID   SERIAL NOT NULL ,
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
	lesson_ID     SERIAL NOT NULL ,
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
	eval_ID     SERIAL NOT NULL ,
	coeff       FLOAT  NOT NULL ,
	lesson_ID   INT  NOT NULL  ,
	CONSTRAINT evaluation_PK PRIMARY KEY (eval_ID)

	,CONSTRAINT evaluation_lesson_FK FOREIGN KEY (lesson_ID) REFERENCES public.lesson(lesson_ID)
)WITHOUT OIDS;


------------------------------------------------------------
-- Table: grade
------------------------------------------------------------
CREATE TABLE public.grade(
	grade_ID     SERIAL NOT NULL ,
	grade        FLOAT  NOT NULL ,
	eval_ID      INT  NOT NULL ,
	student_ID   INT  NOT NULL  ,
	CONSTRAINT grade_PK PRIMARY KEY (grade_ID)

	,CONSTRAINT grade_evaluation_FK FOREIGN KEY (eval_ID) REFERENCES public.evaluation(eval_ID)
	,CONSTRAINT grade_student0_FK FOREIGN KEY (student_ID) REFERENCES public.student(student_ID)
)WITHOUT OIDS;
