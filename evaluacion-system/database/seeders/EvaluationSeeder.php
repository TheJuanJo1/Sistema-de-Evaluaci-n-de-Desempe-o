<?php

namespace Database\Seeders;

use App\Models\Competency;
use App\Models\Question;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    public function run(): void
    {
        // --- DOCENTES ---
        
        // Competencia 1: Estratégico y Misional
        $c1 = Competency::create([
            'name' => 'Estratégico y Misional',
            'type' => 'Docente',
            'evaluator_role' => 'Rector',
        ]);
        
        $this->createQuestions($c1, [
            'El docente demuestra compromiso, sentido de pertenencia y una actitud acogedora y respetuosa hacia la institución y la comunidad educativa',
            'El docente participa o dirige con responsabilidad las reflexiones con estudiantes y la comunidad educativa',
            'El docente demuestra, con sus actitudes y comportamientos, un interés legítimo por la identidad Bethlemita',
            'El docente participa activamente y con alegría en las actividades programadas por la institución',
            'El docente se integra a los propósitos institucionales para atender las necesidades y mejorar la calidad de los servicios que ofrece la institución',
            'Al participar en las actividades institucionales, el docente muestra interés, compromiso y un sentido de pertenencia, sirviendo como un colaborador activo y respetuoso',
        ]);

        // Competencia 2: Académico Comportamental
        $c2 = Competency::create([
            'name' => 'Académico Comportamental',
            'type' => 'Docente',
            'evaluator_role' => 'Coord. Académico',
        ]);

        $this->createQuestions($c2, [
            'El docente investiga, indaga y profundiza en temas de su área de desempeño y fortalece su práctica profesional',
            'El docente organiza y ejecuta sus responsabilidades de forma autónoma y con liderazgo, asegurando la calidad en la planeación, ejecución y evaluación . De acuerdo al modelo pedagógico',
            'La planeación del docente incluye metas de aprendizaje, estrategias, recursos y criterios claros de evaluación',
            'El docente demuestra capacidad para anticiparse a los desafíos y generar estrategias efectivas para el logro de sus objetivos profesionales, institucionales y personales',
            'El docente busca y encuentra soluciones efectivas a los problemas que enfrenta en su desempeño profesional',
            'El docente entrega los informes, calificaciones y otros registros en los plazos establecidos por la institución',
            'El docente demuestra un adecuado manejo de grupo, generando espacios de interacción ordenados y disciplinados que favorecen el trabajo en equipo',
            'El docente ayuda en la adecuada mediación de conflictos en las relaciones interpersonales, actuando de manera imparcial, crítica y objetiva, y evita juicios de valor o comentarios que desmejoren el clima institucional',
            'El docente muestra un trato empático y respetuoso con todos los miembros de la comunidad, evitando comentarios negativos o juicios de valor inapropiados',
            'El docente aplica activamente metodologías que promueven la participación efectiva de los estudiantes y el desarrollo de sus competencias interpersonales',
            'El docente diseña actividades y utiliza recursos didácticos variados que se adaptan a los diferentes estilos de aprendizaje de los estudiantes, logrando que el aprendizaje sea significativo y relevante',
            'El docente proporciona retroalimentación oportuna, clara y constructiva que ayuda efectivamente a los estudiantes a comprender sus errores y a mejorar su proceso de aprendizaje',
            'El docente realiza un seguimiento permanente y objetivo al progreso formativo de sus estudiantes',
            'El docente aprovecha las oportunidades de cualificación ofrecidas por la institución para mejorar el desempeño profesional',
        ]);

        // Competencia 3: Cultura y Calidad
        $c3 = Competency::create([
            'name' => 'Cultura y Calidad',
            'type' => 'Docente',
            'evaluator_role' => 'Coord. Convivencia',
        ]);

        $this->createQuestions($c3, [
            'El docente participa y genera activamente espacios de colaboración para intercambiar ideas y buscar estrategias que promuevan el desarrollo institucional, profesional y comunitario',
            'Propone y apoya iniciativas de mejora que benefician a la institución en su conjunto (procesos, programas, ambiente laboral)',
            'El docente promueve de forma activa los valores éticos y morales de la institución a través de su conducta y enseñanza',
            'El docente utiliza y cuida adecuadamente los elementos de trabajo que se le entregan y colabora con el orden y el buen estado de las aulas, sala de maestros y cafetín',
            'El docente es puntual al iniciar sus clases y en todas las actividades institucionales (ej. eucaristías, reflexiones, capacitaciones)',
            'El docente cumple de manera oportuna y organizada con la entrega de unidades de aprendizaje, informes académicos y notas dentro de los plazos establecidos por la institución',
        ]);

        // Competencia 4: Seguridad y Salud en el Trabajo
        $c4 = Competency::create([
            'name' => 'Seguridad y Salud en el Trabajo',
            'type' => 'Docente',
            'evaluator_role' => 'Talento Humano',
        ]);

        $this->createQuestions($c4, [
            'El docente conoce y tiene clara la Política de Seguridad y Salud en el Trabajo (SST), y esto se refleja activamente en su comportamiento diario y en sus prácticas laborales',
            'El docente procura el cuidado integral de su salud y suministra información clara, completa y veraz sobre su estado de salud cuando es requerido por la institución',
            'El docente participa activamente en las actividades de prevención de riesgos laborales (capacitaciones, simulacros, etc.) realizadas en el colegio',
            'El docente acata el reglamento interno y las normas institucionales',
            'Con sus comportamientos y actitudes, el docente promueve el compañerismo, servicio y respeto, e influye de manera positiva en los equipos de trabajo para el logro de los objetivos comunes',
        ]);

        // --- ADMINISTRATIVOS ---

        // Competencia 1: Institucionales
        $a1 = Competency::create([
            'name' => 'Institucionales',
            'type' => 'Administrativo',
            'evaluator_role' => 'Rector',
        ]);

        $this->createQuestions($a1, [
            'Se evidencia sentido de pertenencia por la institución, es comprometido, acogedor y respetuoso',
            'Participa activamente y con alegría en las actividades programadas por la institución',
            'Demuestra interés por todo lo relacionado con la identidad Bethlemita, a través de sus comportamientos y actitudes',
            'Hace uso adecuado de los medios de comunicación e información de la institución',
            'Realiza con responsabilidad las reflexiones con sus estudiantes',
            'Es colaborador, servicial y solidario con quien lo necesite',
        ]);

        // Competencia 2: Específicas del Cargo
        $a2 = Competency::create([
            'name' => 'Específicas del Cargo',
            'type' => 'Administrativo',
            'evaluator_role' => 'Coord. Académico',
        ]);

        $this->createQuestions($a2, [
            'Se evidencia planeación y organización en su trabajo',
            'Es conciliador al momento de presentarse conflictos',
            'Mantiene una comunicación fluida con su jefe inmediato',
            'Posee capacidad de anticiparse al entorno y generar estrategias de respuesta para la consecución de las metas y objetivos institucionales, personales, profesionales y comunitarios',
            'Diligencia registros y entrega oportunamente los documentos',
            'Crea un ambiente propicio con sus compañeros de trabajo a través del fomento de un clima armonioso y de mutuo respeto',
            'Investiga, indaga y profundiza de manera proactiva en los temas de su entorno o área de desempeño, y aplica los mismo a su labor como profesional',
            'En la práctica demuestra el conocimiento para realizar sus funciones según las especificaciones del cargo',
            'Muestra habilidad para recordar y seguir instrucciones, viveza mental y velocidad de reacción',
            'Aporta a la mejora continua de los procesos institucionales',
        ]);

        // Competencia 3: Actitudinales
        $a3 = Competency::create([
            'name' => 'Actitudinales',
            'type' => 'Administrativo',
            'evaluator_role' => 'Coord. Convivencia',
        ]);

        $this->createQuestions($a3, [
            'Dirige al personal con disciplina y a la vez con apertura para que este pueda dar aportes y sugerencias de mejoramiento',
            'Ajusta sus planes o programas a la medida que otros aportes enriquecen sus propuestas',
            'Realiza las tareas en el tiempo establecido',
            'Se evidencia buena presentación personal de acuerdo al perfil Bethlemitas',
            'Conoce y maneja el sistema de gestión de calidad de acuerdo a su rol',
            'Acata las recomendaciones de seguridad y salud en el trabajo e informa su estado de salud',
        ]);
    }

    private function createQuestions($competency, $questions)
    {
        foreach ($questions as $text) {
            Question::create([
                'competency_id' => $competency->id,
                'text' => $text,
            ]);
        }
    }
}
