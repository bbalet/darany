using System;

namespace CSharpClient
{
    /// <summary>
    /// Timeslot (booking of a meeting room)
    /// </summary>
    public class Timeslot
    {
        public int Id { get; set; }
        public DateTime StartDate { get; set; }
        public DateTime EndDate { get; set; }
        public string status_name { get; set; }
        public string creator_name { get; set; }
        public string note { get; set; }
    }
}
