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

        private void cmdIsAvailable_Click(object sender, EventArgs e)
        {
            using (WebClient l_objClient = new WebClient())
            {
                l_objClient.BaseAddress = txtBaseURL.Text;
                //Get the room id from the selected line
                int l_intRoomID = (int) tblLeaves.SelectedRows[0].Cells[0].Value;
                string l_strStatusURL = "rooms/" + l_intRoomID + "/status";
                try
                {
                    byte[] l_objResponse = l_objClient.UploadValues(l_strStatusURL, new NameValueCollection()
                   {
                       { "login", txtLogin.Text },
                       { "password", txtPassword.Text }
                   });
                    string l_strResult = System.Text.Encoding.UTF8.GetString(l_objResponse);
                    Availability l_objAvailability = JsonConvert.DeserializeObject<Availability>(l_strResult);
                    string l_strMessage = "The room #" + l_intRoomID + " is ";
                    if (l_objAvailability.IsFree)
                    {
                        l_strMessage += "available.\n";
                        if (l_objAvailability.NextState != null)
                            l_strMessage += "But it will be no more available on " + l_objAvailability.NextState.ToString();
                    }
                    else
                    {
                        l_strMessage += "already booked.\n";
                        if (l_objAvailability.NextState != null)
                            l_strMessage += "But it will available on " + l_objAvailability.NextState.ToString();
                    }
                    MessageBox.Show(l_strMessage);
                }
                catch (WebException l_objException)
                {
                    MessageBox.Show(l_objException.Message);
                }
            }

        }

        private void tblLeaves_SelectionChanged(object sender, EventArgs e)
        {
            if (tblLeaves.SelectedRows.Count > 0)
            {
                cmdIsAvailable.Enabled = true;
            }
            else
            {
                cmdIsAvailable.Enabled = false;
            }
        }
    }
}
