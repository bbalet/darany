using System;
using System.Collections.Generic;
using System.Collections.Specialized;
using System.Net;
using System.Windows.Forms;
using Newtonsoft.Json;

namespace CSharpClient
{
    public partial class frmMain : Form
    {
        public frmMain()
        {
            InitializeComponent();
        }

        /// <summary>
        /// On double click on a datatable cell, load the entire leave object from REST API
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void tblLeaves_CellDoubleClick(object sender, DataGridViewCellEventArgs e)
        {
            int l_intRoomID = Convert.ToInt32(this.tblLeaves.Rows[e.RowIndex].Cells[0].Value.ToString());
            string l_strTimeslotsURL = "rooms/" + l_intRoomID + "/timeslots";
            using (WebClient l_objClient = new WebClient())
            {
                l_objClient.BaseAddress = txtBaseURL.Text;
                try
                {
                    byte[] l_objResponse = l_objClient.UploadValues(l_strTimeslotsURL, new NameValueCollection()
                   {
                       { "login", txtLogin.Text },
                       { "password", txtPassword.Text }
                   });
                    string l_strResult = System.Text.Encoding.UTF8.GetString(l_objResponse);
                    List<Timeslot> l_lstTimeslots = JsonConvert.DeserializeObject<List<Timeslot>>(l_strResult);
                    frmTimeslotsView l_objLeaveView = new frmTimeslotsView(l_lstTimeslots);
                    l_objLeaveView.ShowDialog();
                }
                catch (WebException l_objException)
                {
                    MessageBox.Show(l_objException.Message);
                }
            }
        }

        /// <summary>
        /// Get the list of rooms (REST API)
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void cmdGetRooms_Click(object sender, EventArgs e)
        {
            using (WebClient l_objClient = new WebClient())
            {
                l_objClient.BaseAddress = txtBaseURL.Text;
                try
                {
                   byte[] l_objResponse = l_objClient.UploadValues("rooms", new NameValueCollection()
                   {
                       { "login", txtLogin.Text },
                       { "password", txtPassword.Text }
                   });
                    string l_strResult = System.Text.Encoding.UTF8.GetString(l_objResponse);
                    List<Room> l_lstRooms = JsonConvert.DeserializeObject<List<Room>>(l_strResult);
                    this.tblLeaves.Rows.Clear();
                    foreach (Room l_objRoom in l_lstRooms)
                    {
                        tblLeaves.Rows.Add(l_objRoom.Id,
                            l_objRoom.location_name,
                            l_objRoom.manager_name,
                            l_objRoom.Name,
                            l_objRoom.Floor,
                            l_objRoom.Description);
                    }
                }
                catch (WebException l_objException)
                {
                    MessageBox.Show(l_objException.Message);
                }
            }
        }

    }
}
