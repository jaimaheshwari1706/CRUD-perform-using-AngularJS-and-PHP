import { AfterViewInit, Component, OnInit } from '@angular/core';
declare var $: any; // Keep jQuery declaration if needed

@Component({
  selector: 'app-orgtable',
  templateUrl: './orgtable.component.html',
  styleUrls: ['./orgtable.component.css']
})
export class OrgtableComponent implements OnInit, AfterViewInit {

  constructor() { }

  ngOnInit(): void {
  }

  ngAfterViewInit(): void {
    this.loadtable();
  }

  loadtable(): void {
      if ($('#table2').length > 0) {
        $('#table2').DataTable({
          processing: true,
          serverSide: true,
          // pageLength: 5,
          // lengthMenu: [5, 10, 25, 50],
          info: false,
          dom: 'Bfrtip.',
          buttons: [
            {
              extend: 'csvHtml5',
              // text: 'Export All',
              exportOptions: {
                modifier: {
                  page: 'all'
                },
                columns: ':visible'
              }
            }
          ],
          ajax: {
            url: 'http://localhost/HRMSGLOBALANG/angular/datatable2',
            type: 'POST',
            // Add the data option to send the organization_id
            data: function (d: any) {
              // d contains the default DataTables parameters (start, length, search, order, etc.)
              // Add your custom parameter here:
              // d.organization_id = 3;
              // You can add other parameters like this:
              // d.another_param = some_value;
            }
          },
          columns: [
            { data: 'Id', title: 'ID' },
            { data: 'Name', title: 'Name' },
            { data: 'Code', title: 'Code', orderable: false },
            { data: 'OrganizationId', title: 'Organization ID', orderable: false },
            { data: 'gender', title: 'Gender', orderable: false },
          ]
        });
      }

  }}
