import {AfterViewInit, Component, OnInit, ViewChild} from '@angular/core';
import {ApiService} from "../service/api.service";
import {MatPaginator} from '@angular/material/paginator';
import {MatSort} from '@angular/material/sort';
import {MatTableDataSource} from '@angular/material/table';
import {MatDialog} from "@angular/material/dialog";
import {DialogComponent} from "../dialog/dialog.component";
import {DialogDeleteComponent} from "../dialog-delete/dialog-delete.component";


@Component({
  selector: 'app-table',
  templateUrl: './table.component.html',
  styleUrls: ['./table.component.scss']
})
export class TableComponent implements OnInit
{
  displayedColumns: string[] = ['id', 'name', 'price', 'floorLimit', 'fees', 'action'];
  dataSource!: MatTableDataSource<any>;

  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  constructor(private api: ApiService, private dialog:MatDialog) {}

  ngOnInit(): void {
    this.getAllCollections()
  }

  getAllCollections() {
    this.api.getCollections().subscribe({
      next: (res) => {
        this.dataSource = new MatTableDataSource(res);
        this.dataSource.paginator = this.paginator;
        this.dataSource.sort = this.sort
      },
      error: (err) => {
        console.log(err)
      }
    })
  }

  editCollection(row: any) {
    this.dialog.open(DialogComponent, {
      width: '30%',
      data: row
    })
  }

  applyFilter(event: Event) {
    const filterValue = (event.target as HTMLInputElement).value;
    this.dataSource.filter = filterValue.trim().toLowerCase();

    if (this.dataSource.paginator) {
      this.dataSource.paginator.firstPage();
    }
  }

  openDialog() {
    this.dialog.open(DialogComponent, {
      width:'30'
    })
  }

  openDeleteDialog(row: any) {
    this.dialog.open(DialogDeleteComponent, {
      width:'30',
      data: row
    })
  }
}
