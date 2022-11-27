<?php

namespace App\Services;

use Exception;

/**
 * this class is responsible for fetching personal intro data from memory
 */

final class HardcodedPersonalIntroService implements PersonalIntroServiceInterface
{
    public function __construct()
    {
        $this->setSections([
            [
                'heading' => 'A mon propos',
                'paragraphs' => [
                    "En tant que développeur, j'aime l'efficience, l'optimisation, les processus d'amélioration continue.",
                    'Je veux consommer et produire des solutions simples et intuitives, automatisées.',
                    "Professionel dans le monde du développement web depuis cinq ans, je me sens particulièrement accompli quand mon travail permet d'améliorer au mieux le quotidien de mes partenaires, clients, collègues, élèves, etc.",
                    "J'aime aborder mes projets en m'appuyant sur des tests automatisés et sur un feedback régulier des utilisateurs finaux de mes solutions.",
                    'Sur une codebase existante ou from scratch, donnez-moi des problèmes à résoudre ! 🔧'
                ]
            ],
            [
                'heading' => 'Ma proposition de projet',
                'paragraphs' => [
                    "Un projet réussi est un projet clôturé sans y perdre sa tête ou son temps. C'est là que j'interviens, en m'assurant que nous irons loin ensemble, itération après itération. 🔁",
                    "Vous avez besoin d'un accompagnement personnalisé ? De prestations de consulting IT ou encore de formation ?",
                    "En travaillant avec moi, vous serez assuré que mon expérience, mon expérience, mon opiniâtreté, ainsi que mon aisance relationnelle avec vous et vos équipes nous permettront d'atteindre vos objectifs. 🚀",
                    'Je vous accompagnerai avec expertise, rigueur, passion, et enthousiasme tout le long du cycle de vie de votre application.',
                    "En tant que développeur full stack, mon expérience m'a permis d'implémenter toutes sortes de solutions avec diverses technologies: PHP (natif, Laravel, ou Symfony), la MERN stack, des bases de données documents ou relationnelles, de la containerisation avec Docker et Kubernetes, des CI/CD pipelines, la Google Cloud Platform...",
                    "En tant que formateur en développement web pour des établissements reconnus (<a href=\"https://openclassrooms.com/fr/\" target=\"_blank\">OpenClassrooms</a>, <a href=\"https://www.wildcodeschool.com/en-GB\" target=\"_blank\">Wild Code School</a>, <a href=\"https://www.udacity.com/\" target=\"_blank\">Udacity</a>), j'ai acquis les techniques pédagogiques nécessaires pour enseigner mes connaissances à tous types de publics: j'adore transmettre !"
                ]
            ],
            [
                'heading' => 'Mon expérience professionnelle',
                'paragraphs' => [
                    "Permettez-moi de partager avec vous une liste du type de fonctionnalités et missions que j'ai pu réaliser: 🏆",
                ],
                'listItems' => [
                    'La création de microservices découplés sur la Google Cloud Platform utilisant la technologie Pub/Sub.',
                    "Un template de facturation pour une application PHP d'agenda à destination de cabinets médicaux.",
                    "Un chatbot utilisant l'API Telegram pour prendre des congés de manière automatisée.",
                    "Un prototype de solution de reconnaissance faciale utilisant des librairies Python en open-source pour faire du contrôle d'accès à des zones restreintes.",
                    "Une fonctionnalité cross-platforms de vérification d'habilitations pour des techniciens intervenant sur des sites industriels, et conditionnant la planification de leurs éventuels déplacements sur les différentes sites.",
                    "Le mentoring d'étudiants afin de les aider à obtenir leur titre certifiant de développeur web."
                ]
            ]
        ]);
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function getSections(): array
    {
        return $this->_sections;
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function setSections(array $sections): void
    {
        $this->_sections = $sections;
    }
}
