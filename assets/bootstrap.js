import { startStimulusApp } from '@symfony/stimulus-bundle';
import ProjectBoardController from './controllers/project_board_controller';

export const app = startStimulusApp();

app.register('ProjectBoardController', ProjectBoardController);