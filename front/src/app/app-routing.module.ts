import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {TableComponent} from "./table/table.component";
import {CalculComponent} from "./calcul/calcul.component";
import {TracklistComponent} from "./tracklist/tracklist.component";

import { CommonModule } from '@angular/common';

const routes: Routes = [
  { path: '', redirectTo: '/table', pathMatch: 'full' },
  { path: 'table', component:  TableComponent},
  { path: 'calcul', component:  CalculComponent},
  { path: 'trackList', component: TracklistComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes),CommonModule],
  exports: [RouterModule],
  declarations: []
})
export class AppRoutingModule { }
