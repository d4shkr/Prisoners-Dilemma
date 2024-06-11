CREATE DATABASE IF NOT EXISTS db;

USE db;

DROP TABLE IF EXISTS TournamentMembers;
DROP TABLE IF EXISTS Tournaments;
DROP TABLE IF EXISTS Players;
DROP TABLE IF EXISTS Dilemma;

CREATE TABLE Dilemma
(
    -- Game Data:
    GameId VARCHAR(36) NOT NULL PRIMARY KEY,
    CurrentRound INT,
    GamePhase TEXT, -- Status: 'Running' if game is running, 'Finished' if game is finished
    -- Player Data:
    PayoffHistory_Player1 JSON,
    PayoffHistory_Player2 JSON,
    Score_Player1 INT, -- Total score for Pl1
    Score_Player2 INT, 
    Status_Player1 INT, -- Status: -1 if hasn't joined the game yet, 0 if didn't choose yet, 1 if Betrayed, 2 if Cooperated
    Status_Player2 INT,
    Message_Player1 TEXT, -- Message to display to the player
    Message_Player2 TEXT,
    UpToDate_Player1 BOOLEAN, -- false if visuals need to be updated
    UpToDate_Player2 BOOLEAN,
    TournamentMemberId1 VARCHAR(36), -- Tournament member ID, associated with this game. NULL if player1 is not a tournament member.
    TournamentMemberId2 VARCHAR(36) -- Tournament member ID, associated with this game. NULL if player2 is not a tournament member.
    -- Settings:
    MaxRounds INT, -- Total number of rounds in the game
    BothBetrayPayoff INT, -- Payoff if both players betray
    BothCooperatePayoff INT, -- Payoff if both players cooperate
    WasBetrayedPayoff INT, -- Payoff if player cooperated and was betrayed
    HasBetrayedPayoff INT, -- Payoff if player betrayed and the other player cooperated
    MaxRoundsKnown BOOLEAN -- false if number of rounds is hidden from the players
);

CREATE TABLE Players
(
    PlayerId VARCHAR(36) NOT NULL PRIMARY KEY,
    GameId VARCHAR(36) REFERENCES Dilemma(GameId),
    PlayerNum INT -- 1 or 2
);

CREATE TABLE Tournaments (
    TournamentId VARCHAR(36) NOT NULL PRIMARY KEY,
    TournamentMemberIds JSON, -- JSON array of TournamentMemberId from TournamentMembers table
    TournamentPhase TEXT, -- 'Waiting' if less than NumberOfMembers members have joined the tournament, 'In Progress' when started, 'Complete' when complete
    -- Tournaments Settings:
    NumberOfMembers INT,
    NumberOfGamesPerMember INT, -- How many games each member will play
    -- Game Settings:
    MaxRounds INT, -- Total number of rounds in the game
    BothBetrayPayoff INT, -- Payoff if both players betray
    BothCooperatePayoff INT, -- Payoff if both players cooperate
    WasBetrayedPayoff INT, -- Payoff if player cooperated and was betrayed
    HasBetrayedPayoff INT, -- Payoff if player betrayed and the other player cooperated
    MaxRoundsKnown BOOLEAN -- false if number of rounds is hidden from the players
);

CREATE TABLE TournamentMembers (
    TournamentMemberId VARCHAR(36) NOT NULL PRIMARY KEY,
    TournamentId VARCHAR(36) REFERENCES Tournaments(TournamentId),
    Curr_PlayerId VARCHAR(36) REFERENCES Players(PlayerId),
    MemberName VARCHAR(16),
    Score INT, -- Member's Total Score in the tournament
    NumberOfPlayedGames INT, 
    PreviousOpponentIds JSON, -- JSON array of TournamentMemberId of previous opponents (is FIFO clamped to NumberOfMembers - 3)
    IsAvailable BOOLEAN -- True if member can join a new game
);
